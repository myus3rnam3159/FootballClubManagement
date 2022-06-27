<?php

require_once('../model/Player.php');

try {
    $player = new Player(1021, "Karim Benzema", 106, "1987-01-01 00:00:00", "FW", "FRANCE", 9);
    header('Content-type: application/json;charset=UTF-8');
    echo json_encode($player->returnPlayerAsArray());
}
catch(PlayerException $ex) {
    echo "Error: ".$ex->getMessage();
}

?>