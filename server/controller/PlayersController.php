<?php
require_once('../db/db.php');
require_once('../model/Player.php');
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

#Cập nhật thông tin cầu thủ
    if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        
        if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            sendResponse(400, false, "Content Type header not set to JSON");
            exit;
        }
        $rawPatchData = file_get_contents('php://input');
        #Test
        #echo $rawPatchData;
        #exit;
        $jsonData = json_decode($rawPatchData);

        if(!$jsonData) {
            sendResponse(400, false, "Request body is not valid JSON");
            exit;
        }


        $playerid = $jsonData->playerid;

        if($playerid == '' || !is_numeric($playerid)){
            sendResponse(400, false, "Player id can not be blank and must be numeric");
            exit;
        }

        $fullnameUpdated = false;
        $clubidUpdated = false;
        $positionUpdated = false;
        $nationalityUpdated = false;
        $numberUpdated = false;

        #setup trường cần cập nhật trong query
        $queryFields = "";

        if(isset($jsonData->fullname)){
            $fullnameUpdated = true;
            $queryFields .= "fullname = :fullname, ";
        }

        #Chú ý: clubid đã có trong db
        if(isset($jsonData->clubid)){
            $clubidUpdated = true;
            $queryFields .= "clubid = :clubid, ";
        }

        if(isset($jsonData->position)){
            $positionUpdated = true;
            $queryFields .= "position = :position, ";
        }

        if(isset($jsonData->nationality)){
            $nationalityUpdated = true;
            $queryFields .= "nationality = :nationality, ";
        }

        if(isset($jsonData->number)){
            $numberUpdated = true;
            $queryFields .= "number = :number, ";
        }

        $queryFields = rtrim($queryFields, ", ");

        if(
            $fullnameUpdated === false && 
            $clubidUpdated === false && 
            $positionUpdated === false && 
            $nationalityUpdated === false && 
            $numberUpdated === false){
                sendResponse(400, false, "No player fields provided");
                exit;
        }

        try{
            #Kiểm tra xem player có tồn tại hay không
            $query = $readDB->prepare('select playerid from player where playerid = :playerid;');
            $query->bindParam(':playerid', $playerid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();
            if($rowCount === 0){
                sendResponse(404, false, "No player found to update");
                exit;
            }
            #Nếu tồn tại player
            $queryString = "update player set ".$queryFields." where playerid = :playerid;";

            #Chuẩn bị query
            $query = $writeDB->prepare($queryString);

            #Chèn tham số
            if($fullnameUpdated === true){
                $query->bindParam(':fullname', $jsonData->fullname, PDO::PARAM_STR);
            }
            if($clubidUpdated === true){
                $query->bindParam(':clubid', $jsonData->clubid, PDO::PARAM_INT);
            }
            if($positionUpdated === true){
    
                $query->bindParam(':position', $jsonData->position, PDO::PARAM_STR);
            }
            if($nationalityUpdated === true){
                $query->bindParam(':nationality', $jsonData->nationality, PDO::PARAM_STR);
            }
            if($numberUpdated === true){
                $query->bindParam(':number', $jsonData->number, PDO::PARAM_STR);
            }

            #Test
            #echo $playerid;
            #exit;

            $query->bindParam(':playerid', $playerid, PDO::PARAM_INT);
            $query->execute();

            #Lấy số dòng bị updated
            $rowCount = $query->rowCount();
            #Trường hợp không có dòng nào được update
            if($rowCount === 0){
                sendResponse(400, false, "Player not updated - given values maybe the same as the stored values");
                exit;
            }
            #Lấy dòng mới cập nhật
            $query = $readDB->prepare('select * from player where playerid = :playerid;');
            $query->bindParam(':playerid', $playerid, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            if($rowCount === 0){
                sendResponse(400, false, "No player found");
                exit;
            }
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $player = new Player($row['PLAYERID'], $row['FULLNAME'], $row['CLUBID'], $row['DOB'], $row['POSITION'], $row['NATIONALITY'], $row['NUMBER']);
            
            #Trả về thành công
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Player updated");
            $response->setData($player->returnPlayerAsArray());
            $response->send();
            exit;

        }
        catch(PlayerException $ex) {
            sendResponse(400, false, $ex->getMessage());
            exit;
        }
        catch(PDOException $ex) {
            error_log("Database Query Error: ".$ex, 0);
            sendResponse(400, false, $ex->getMessage());
            exit;
        }
    }
#Lấy players có phân trang
if(array_key_exists("pageIndex", $_GET)){
    #Số dòng tối đa cho 1 trang
    $limitPerPage = 10;
    $page = $_GET['pageIndex'];

    if($page == '' || !is_numeric($page)){
        sendResponse(400, false, "Page number cannot be blank and must be numeric");
        exit;
    }
    #Các thuộc tính tìm kiếm
    $search = null;
    $term = null;
    #câu lạc bộ
    $club = null;
    $clubid = null;
    #quốc tịch - số áo
    $nationality = null;
    $number = null;
    #Query dùng cho tìm kiếm có tham số
    $statement = 'select * from player where ';
    #Nếu tìm theo tên
    if(array_key_exists("search", $_GET)){
        $search = $_GET['search'];
        $statement .= 'fullname like :term and ';
    }
    #Tìm theo club
    if(array_key_exists("club", $_GET)){
        $club = $_GET['club'];
        $statement .= 'clubid = :clubid and ';
    }
    #Tìm theo quốc tịch
    if(array_key_exists("nationality", $_GET)){
        $nationality = $_GET['nationality'];
        $statement .= 'nationality = :nationality and ';
    }
    #Tìm theo số áo
    if(array_key_exists("number", $_GET)){
        $number = $_GET['number'];
        $statement .= 'number = :number and ';
    }
    #Kết thúc query
    $statement .= 'true;';
    #Trường hợp không có tham số
    if(is_null($search) && is_null($club) && is_null($nationality) && is_null($number)){
        $firstRow = ((int) $page - 1) * $limitPerPage;
        
        try {
            $query = $readDB->prepare('select * from player limit :firstrow, :count;');
            $query->bindParam(':firstrow', $firstRow, PDO::PARAM_INT);
            $query->bindParam(':count', $limitPerPage, PDO::PARAM_INT);
            $query->execute();
            
            $rowCount = $query->rowCount();
            $playerArray = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $player = new Player($row['PLAYERID'], $row['FULLNAME'], $row['CLUBID'], $row['DOB'], $row['POSITION'], $row['NATIONALITY'], $row['NUMBER']);
                $playerArray[] = $player->returnPlayerAsArray();
            }

            #Có thể điều chỉnh phân trang theo angular dưới đây
            $returnArray = array();
            $returnArray['pageIndex'] = (int)$page;
            $returnArray['pageSize'] = $limitPerPage;
            #Đếm tất cả các dòng có trong bảng player
            $query = $readDB->prepare('select count(playerid) as playerRows from player;');
            $query->execute();
            $rows = $query->fetch(PDO::FETCH_ASSOC);

            $returnArray['count'] = intval($rows['playerRows']);
            $returnArray['players'] = $playerArray;
            #Trả về kết quả
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnArray);
            $response->send();
            exit;

        }  catch(PlayerException $ex){
            sendResponse(500, false, $ex->getMessage());
            exit;
        } catch(PDOException $ex){
            error_log("Database query error - ". $ex, 0);
            sendResponse(500, false, "Failed to get players");
            exit;
        }
    }

    #Pass tham số vào query
    try{
        $query = $readDB->prepare($statement);
        if(!is_null($search)){
            $term = "%$search%";
            $query->bindParam(':term', $term, PDO::PARAM_STR);
        }
        if(!is_null($club)){
            $clubid = intval($club);
            $query->bindParam(':clubid', $clubid, PDO::PARAM_INT);
        }
        if(!is_null($nationality)){
            $query->bindParam(':nationality', $nationality, PDO::PARAM_STR);
        }
        if(!is_null($number)){
            $query->bindParam(':number', $number, PDO::PARAM_STR);
        }

        $query->execute();
        $rowCount = $query->rowCount();
        $playerArray = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $player = new Player($row['PLAYERID'], $row['FULLNAME'], $row['CLUBID'], $row['DOB'], $row['POSITION'], $row['NATIONALITY'], $row['NUMBER']);
            $playerArray[] = $player->returnPlayerAsArray();
        }
        $returnArray = array();
        $returnArray['pageIndex'] = (int)$page;
        $returnArray['pageSize'] = $limitPerPage;
        $returnArray['count'] = count($playerArray);
        #Phân trang = cách cắt array data trả về
        $start = $limitPerPage * (int)$page - $limitPerPage;
        $returnArray['players'] = array_slice($playerArray, $start, $limitPerPage);
        #Trả về kết quả
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->toCache(true);
        $response->setData($returnArray);
        $response->send();
        exit;

    } catch(PlayerException $ex){
        sendResponse(500, false, $ex->getMessage());
        exit;
    } catch(PDOException $ex){
        error_log("Database query error - ". $ex, 0);
        sendResponse(500, false, "Failed to get players");
        exit;
    }
}
#Lấy player theo id
if(array_key_exists("playerid", $_GET)){

    #Lấy player id từ chuỗi url
    $playerid = $_GET['playerid'];

    #Kiểm tra xem playerid không null và là dạng số, nếu không trả về json error
    if($playerid == '' || !is_numeric($playerid)){
        sendResponse(400, false, "Player ID cannot be blank or must be numeric");
        exit;
    }
    #Nếu yêu cầu xóa player
    if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
        #Kiểm tra có tồn tại playerid không
        try{
            $query = $readDB->prepare(
                'select * from player where playerid = :playerid;'
            );
            $query->bindParam(':playerid', $playerid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();
            if($rowCount === 0){
                sendResponse(404, false, "No player found to delete");
                exit;
            }

            $query = $writeDB->prepare('delete from player where playerid = :playerid');
            $query->bindParam(':playerid', $playerid, PDO::PARAM_INT);
            $query->execute();

            sendResponse(200, true, "Player deleted");
            exit;
            
        }
        catch(PlayerException $ex){
            sendResponse(500, false, $ex->getMessage());
            exit;
        }
        catch(PDOException $ex){
            sendResponse(500, false, $ex->getMessage());
            exit;
        }
    }
    #Nếu request với method get
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        #Thực hiện query với db
        try{
            #Tạo câu query
            $query = $readDB->prepare('select * from player where playerid = :playerid;');
            $query->bindParam(':playerid', $playerid, PDO::PARAM_INT);
            $query->execute();
            #Đếm dòng trả về
            $rowCount = $query->rowCount();
            #Mảng chứa các hàng dưới dạng json khi được trả về
            $playerArray = array();
            if($rowCount === 0){
                sendResponse(404, false, "Player not found");
                exit;
            }
            #Duyệt dòng trả về
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $player = new Player($row['PLAYERID'], $row['FULLNAME'], $row['CLUBID'], $row['DOB'], $row['POSITION'], $row['NATIONALITY'], $row['NUMBER']);
                #Thêm vào mảng
                $playerArray[] = $player->returnPlayerAsArray();
            }
            #Trả về kết quả
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($playerArray[0]);
            $response->send();
            exit;

        }
        catch(PlayerException $ex){
            sendResponse(400, false, $ex->getMessage(), null);
            exit;
        }
        catch(PDOException $ex) {
            error_log("Database Query Error: ".$ex, 0);
            sendResponse(400, false, "Failed to get player");
            exit;
        }
    } else{
        sendResponse(405, false, "Request method not allowed");
        exit;
    }
}
#Thêm cầu thủ
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
            #Kiểm tra có trùng số áo + câu lạc bộ hay không - chưa làm.

            #Đếm số dòng Player đã có
            $query = $readDB->prepare('select max(playerid) as playerCount from player;');
            $query->execute();
            $rowCount = $query->fetch(PDO::FETCH_ASSOC);

            #Tạo player id mới
            $newPlayerId = intval($rowCount['playerCount']) + 1;

            #Tạo đối tượng Player mới
            $query = $writeDB->prepare(
                "insert into player 
                (playerid, fullname, clubid, dob, position, nationality, number) 
                values
                (:playerid, :fullname, :clubid, null, :position, :nationality, :number);"
            );
            #Ghép tham số
            
            $query->bindParam(':playerid', $newPlayerId, PDO::PARAM_INT);
            $query->bindParam(':fullname', $jsonData->fullname, PDO::PARAM_STR);
            $query->bindParam(':clubid', $jsonData->clubid, PDO::PARAM_INT);
            $query->bindParam(':position', $jsonData->position, PDO::PARAM_STR);
            $query->bindParam(':nationality', $jsonData->nationality, PDO::PARAM_STR);
            $query->bindParam(':number', $jsonData->number, PDO::PARAM_STR);

            #Thực thi câu lệnh
            $query->execute();

            $rowCount = $query->rowCount();

            #Kiểm tra xem dòng mới đã được tạo chưa
            if($rowCount === 0){
                sendResponse(500, false, "Failed to create player");
                exit;
            }
            #Lấy lại thử dòng dữ liệu mới
            $query = $writeDB->prepare('select * from player where playerid = :playerid');
            $query->bindParam(':playerid', $newPlayerId, PDO::PARAM_INT);
            $query->execute();

            #Kiểm tra có lấy được không
            $rowCount = $query->rowCount();
            if($rowCount === 0){
                sendResponse(500, false, "Failed to retrieve player after creation");
                exit;
            }
            #Tạo lớp Player mới
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $player = new Player($row['PLAYERID'], $row['FULLNAME'], $row['CLUBID'], null, $row['POSITION'], $row['NATIONALITY'], $row['NUMBER']);

            #Trả về thành công
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Player created");
            $response->setData($player->returnPlayerAsArray());
            $response->send();
            exit;


        }
        catch(PlayerException $ex){
            sendResponse(400, false, $ex->getMessage());
            exit;
        }
        catch(PDOException $ex){
            sendResponse(405, false, $ex->getMessage());
            exit;
        }
    }
?>