<?php
require_once('../db/db.php');
require_once('../model/Club.php');
require_once('../model/Response.php');
require_once('../SendRespone.php');


#Kết nối với db
try {
    $writeDB = DB::connectWriteDB();
    $readDB = DB::connectReadDB();
}catch(PDOException $ex) {  
    error_log("Connection Error: ".$ex, 0);
    sendResponse(500, false, "Database connection error");
    exit;
}

#Cập nhật thông tin câu lạc bộ
if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
        sendResponse(400, false, "Content Type header not set to JSON");
        exit;
    }
    $rawPatchData = file_get_contents('php://input');
    $jsonData = json_decode($rawPatchData);

    if(!$jsonData) {
        sendResponse(400, false, "Request body is not valid JSON");
        exit;
    }
    $clubid = $jsonData->clubid;
    if($clubid == '' || !is_numeric($clubid)){
        sendResponse(400, false, "club id can not be blank and must be numeric");
        exit;
    }

    $clubNameUpdated = false;
    $shortNameUpdated = false;
    #setup trường cần cập nhật trong query
    $queryFields = "";

    if(isset($jsonData->clubname)){
        $clubNameUpdated = true;
        $queryFields .= "clubname = :clubname, ";
    }

    if(isset($jsonData->shortname)){
        $shortNameUpdated = true;
        $queryFields .= "shortname = :shortname, ";
    }
    $queryFields = rtrim($queryFields, ", ");
    
    if($clubNameUpdated === false && $shortNameUpdated === false){
        sendResponse(400, false, "No club fields provided");
        exit;
    }

    try{
        #Kiểm tra xem club có tồn tại hay không
        $query = $readDB->prepare('select clubid from club where clubid = :clubid;');
        $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            sendResponse(404, false, "No club found to update");
            exit;
        }
        #Nếu tồn tại club
        $queryString = "update club set " .$queryFields. " where clubid = :clubid;";
        #Chuẩn bị query
        $query = $writeDB->prepare($queryString);
        #Chèn tham số
        if($clubNameUpdated === true){
            $query->bindParam(':clubname', $jsonData->clubname, PDO::PARAM_STR);
        }
        if($shortNameUpdated === true){
            $query->bindParam(':shortname', $jsonData->shortname, PDO::PARAM_STR);
        }
        $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
        $query->execute();

        #Lấy số dòng bị updated
        $rowCount = $query->rowCount();
        #Trường hợp không có dòng nào được update
        if($rowCount === 0){
            sendResponse(400, false, "Club not updated - given values maybe the same as the stored values");
            exit;
        }

        #Lấy dòng mới cập nhật
        $query = $readDB->prepare('select * from club where clubid = :clubid;');
        $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            sendResponse(400, false, "No club found");
            exit;
        }
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $club = new Club($row['CLUBID'], $row['CLUBNAME'], $row['SHORTNAME'], $row['STADIUMID'], $row['COACHID']);

        #Trả về thành công
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->addMessage("Club updated");
        $response->setData($club->returnClubAsArray());
        $response->send();
        exit;

    }
    catch(ClubException $ex){
        sendResponse(400, false, $ex->getMessage());
        exit;
    }
    catch(PDOException $ex) {
        error_log("Database Query Error: ".$ex, 0);
        sendResponse(400, false, $ex->getMessage());
        exit;
    }
}
#Thêm câu lạc bộ
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        try{
            #Kiểm tra content-type hear có dạng json ko
            if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
                sendResponse(400, false, "Content Type header not set to JSON");
                exit;
            }
            #Lấy posted data
            $rawPostData = file_get_contents('php://input');
            #Kiểm tra định dạng json của data có đúng ko?
            $jsonData = json_decode($rawPostData);
            
            if(!$jsonData){
                sendResponse(400, false, "Request body is not valid JSON");
                exit;
            }
            #Kiểm tra trùng tên - chưa làm

            #Đếm số dòng Club đã có
            $query = $readDB->prepare('select count(clubid) as clubCount from club;');
            $query->execute();
            $rowCount = $query->fetch(PDO::FETCH_ASSOC);

            $newClubId = intval($rowCount['clubCount']) + 101;
            $query = $writeDB->prepare(
                "insert into club
                (clubid, clubname, shortname, stadiumid, coachid)
                values
                (:clubid, :clubname, :shortname, null, null);"
            );
            $query->bindParam(':clubid', $newClubId, PDO::PARAM_INT);
            $query->bindParam(':clubname', $jsonData->clubname, PDO::PARAM_STR);
            $query->bindParam(':shortname', $jsonData->shortname, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            #Kiểm tra xem dòng mới đã được tạo chưa
            if($rowCount === 0){
                sendResponse(500, false, "Failed to create club");
                exit;
            }
            #Lấy lại thử dòng dữ liệu mới
            $query = $writeDB->prepare('select * from club where clubid = :clubid');
            $query->bindParam(':clubid', $newClubId, PDO::PARAM_INT);
            $query->execute();
            
            #Kiểm tra có lấy được không
            $rowCount = $query->rowCount();
            if($rowCount === 0){
                 sendResponse(500, false, "Failed to retrieve club after creation");
                 exit;
             }
            #Tạo lớp Player mới
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $club = new Club(
                $row['CLUBID'], 
                $row['CLUBNAME'], 
                $row['SHORTNAME'], 
                $row['STADIUMID'], 
                $row['COACHID']
            );
            #Trả về thành công
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Club created");
            $response->setData($club->returnClubAsArray());
            $response->send();
            exit;
        }
        catch(ClubException $ex){
            sendResponse(400, false, $ex->getMessage());
            exit;
        }
        catch(PDOException $ex){
            sendResponse(405, false, $ex->getMessage());
            exit;
        }
    }
#Lấy một cầu thủ
if(array_key_exists("clubid", $_GET)){
    $clubid = $_GET['clubid'];
    if($clubid == '' || !is_numeric($clubid)){
        sendResponse(400, false, "Club ID cannot be blank or must be numeric");
        exit;
    }
    #Xóa một cầu thủ
    if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
         #Kiểm tra có tồn tại clubid không
         try{
            $query = $readDB->prepare(
                'select * from club where clubid = :clubid;'
            );
            $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();
            if($rowCount === 0){
                sendResponse(404, false, "No player found to delete");
                exit;
            }

            $query = $writeDB->prepare('delete from club where clubid = :clubid');
            $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
            $query->execute();
            sendResponse(200, true, "club deleted");
            exit;
            
        }
        catch(ClubException $ex){
            sendResponse(500, false, $ex->getMessage());
            exit;
        }
        catch(PDOException $ex){
            sendResponse(500, false, $ex->getMessage());
            exit;
        }
    }
    #Lấy một cầu thủ
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        try{
            #Tạo câu query
            $query = $readDB->prepare('select * from club where clubid = :clubid;');
            $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
            $query->execute();
    
            #Đếm dòng trả về
            $rowCount = $query->rowCount();
            if($rowCount === 0){
                sendResponse(404, false, "Club not found");
                exit;
            }
    
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $club = new Club($row['CLUBID'], $row['CLUBNAME'], $row['SHORTNAME'], $row['STADIUMID'], $row['COACHID']);
    
            #Trả về kết quả
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($club->returnClubAsArray());
            $response->send();
            exit;
        }
        catch(ClubException $ex){
            sendResponse(400, false, $ex->getMessage(), null);
            exit;
        }
        catch(PDOException $ex) {
            error_log("Database Query Error: ".$ex, 0);
            sendResponse(400, false, "Failed to get player");
            exit;
        }
    }
   
    
}

if(empty($_GET)){
    try{
        $query = $readDB->prepare('select * from club;');
        $query->execute();

        $clubArray = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $club = new Club($row['CLUBID'], $row['CLUBNAME'], $row['SHORTNAME'], $row['STADIUMID'], $row['COACHID']);
            $clubArray[] = $club->returnClubAsArray();
        }
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->toCache(true);
        $response->setData($clubArray);
        $response->send();
        exit;
    }
    catch(ClubException $ex){
        sendResponse(500, false, $ex->getMessage());
        exit;
    } catch(PDOException $ex){
        error_log("Database query error - ". $ex, 0);
        sendResponse(500, false, "Failed to get clubs");
        exit;
    }
}
#Lấy danh sách clb có phân trang
if(array_key_exists("pageIndex", $_GET)){
    #Số dòng tối đa cho 1 trang
    $limitPerPage = 10;
    $page = $_GET['pageIndex'];
    #Kiểm tra kiểu của page
    if($page == '' || !is_numeric($page)){
        sendResponse(400, false, "Page number cannot be blank and must be numeric");
        exit;
    }
    #Xác định giới hạn page
    $firstRow = ((int) $page - 1) * $limitPerPage;
    #Thực hiện query
    try{
        $query = $readDB->prepare('select * from club limit :firstrow, :count;');
        $query->bindParam(':firstrow', $firstRow, PDO::PARAM_INT);
        $query->bindParam(':count', $limitPerPage, PDO::PARAM_INT);
        $query->execute();
        #Đếm số dòng cho một trang hiện thị
        $rowCount = $query->rowCount();
        $clubArray = array();
        #Thêm dòng vào mảng
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $club = new Club($row['CLUBID'], $row['CLUBNAME'], $row['SHORTNAME'], $row['STADIUMID'], $row['COACHID']);
            $clubArray[] = $club->returnClubAsArray();
        }
        $returnArray = array();
        $returnArray['pageIndex'] = (int)$page;
        $returnArray['pageSize'] = $limitPerPage;
        #Đếm tất cả các dòng có trong bảng club
        $query = $readDB->prepare('select count(clubid) as clubRows from club;');
        $query->execute();
        $rows = $query->fetch(PDO::FETCH_ASSOC);

        $returnArray['count'] = intval($rows['clubRows']);
        $returnArray['clubs'] = $clubArray;

        #Trả về kết quả
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->toCache(true);
        $response->setData($returnArray);
        $response->send();
        exit;


    }
    catch(ClubException $ex){
        sendResponse(500, false, $ex->getMessage());
        exit;
    } catch(PDOException $ex){
        error_log("Database query error - ". $ex, 0);
        sendResponse(500, false, "Failed to get clubs");
        exit;
    }
}
?>