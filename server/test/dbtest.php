<?php
    require_once('../db/db.php');
    require_once('../model/Response.php');

    try{
        $writeDB = DB::connectWriteDB();
        $readDB = DB::connectReadDB();
    } 
    catch(PDOException $ex){
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Database connection error");
        $response->send();
        exit;
    }
?>
        
    
