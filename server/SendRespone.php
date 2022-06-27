<?php
require_once('../model/Response.php');

function sendResponse($statusCode, $successValue, $message)
{
    $response = new Response();
    $response->setHttpStatusCode($statusCode);
    $response->setSuccess($successValue);
    $response->addMessage($message);
    $response->send();
}
?>