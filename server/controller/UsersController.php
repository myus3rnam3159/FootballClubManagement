<?php
require_once('../db/db.php');
require_once('../model/User.php');
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

if(empty($_GET)){
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        
        if(isset($_SERVER['HTTP_AUTHORIZATION']) && strlen($_SERVER['HTTP_AUTHORIZATION']) > 1){
            
            $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];
            try{
                $query = $writeDB->prepare('select * from users where upassword = :upassword');
                $query->bindParam(':upassword', $accesstoken, PDO::PARAM_STR);
                $query->execute();

                $rowCount = $query->rowCount();
                if($rowCount === 0){
                    sendResponse(400, false, 'User not existed with this token');
                    exit;
                }
                //Chỉ có tối đa 1 user với một token
                $row = $query->fetch(PDO::FETCH_ASSOC);
                $user = new User($row['USERID'], $row['UNAME'], $row['UPASSWORD'], $row['USTATUS']);

                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->setData($user->returnUserAsArray());
                $response->send();
                exit;
            }
            catch(PDOException $ex){
                sendResponse(500, false, $ex->getMessage());
                exit;
            }
        }
    }
}

if($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS'){
    sendResponse(405, false, 'Request method not allowed');
    exit;
}

#Lấy post body data
#Kiểm tra content type header có nói rằng đây là json data không?
if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
    sendResponse(400, false, "Content Type header not set to JSON");
    exit;
}

#Kiểm tra tồn tại user trong db không và trả vể thông tin user đó
$rawPostData = file_get_contents('php://input');

#Convert data sang json
$jsonData = json_decode($rawPostData);
#Kiểm tra phát sinh lỗi convert không
if(!$jsonData){
    sendResponse(400, false, "Request body is not valid JSON");
    exit;
}
#Lấy thông tin đăng nhập
$userid = $jsonData->userid;
$password = $jsonData->upassword;

#Thực hiện query
try{
    $query = $readDB->prepare('select * from users where userid = :userid and upassword = :password and ustatus = false;');
    $query->bindParam(':userid', $userid, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();

    #Đếm số dòng trả về
    $rowCount = $query->rowCount();
    #echo $rowCount;
    #exit;

    if($rowCount !== 1) {
        sendResponse(500, false, "There was an error checking the user account - please try again");
        exit;
    }

    $row = $query->fetch(PDO::FETCH_ASSOC);
    $user = new User($userid, $row['UNAME'], $password, true);

    $response = new Response();
    $response->setHttpStatusCode(200);
    $response->setSuccess(true);
    $response->addMessage("User existed");
    $response->setData($user->returnUserAsArray());
    $response->send();
    exit;
}
catch(PDOException $ex){
    sendResponse(500, false, "There was an issue checking a user account - please try again");
}
?>