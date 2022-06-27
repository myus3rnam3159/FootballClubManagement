<?php
require_once('../db/db.php');
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
    try{
        $query = $readDB->prepare('select distinct number from player;');
        $query->execute();

        $numberArray = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $number = $row['number'];
            $numberArray[] = intval($number);
        }
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->toCache(true);
        $response->setData($numberArray);
        $response->send();
        exit;
    }
    catch(PDOException $ex){
        error_log("Database query error - ". $ex, 0);
        sendResponse(500, false, "Failed to get clubs");
        exit;
    }
    
}
?>