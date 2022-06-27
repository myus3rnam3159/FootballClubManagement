<?php
require_once('../model/Club.php');
try {
    $club = new Club(121, "Real Madrid", "RMA", "STD14", 5036);
    header('Content-type: application/json;charset=UTF-8');
    echo json_encode($club->returnClubAsArray());
}
catch(ClubException $ex) {
    echo "Error: ".$ex->getMessage();
}
?>