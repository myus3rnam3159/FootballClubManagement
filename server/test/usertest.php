<?php

require_once('../model/User.php');

try {
    $user = new User('18127057', 'Admin', 'AdminRoot123', True);
    header('Content-type: application/json;charset=UTF-8');
    echo json_encode($user->returnUserAsArray());
}
catch(UserException $ex) {
    echo "Error: ".$ex->getMessage();
}

?>