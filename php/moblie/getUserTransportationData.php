<?php
require_once("../db.php");
require_once("../onlineUser.php");


if(isset($_GET["token"])){
    $token = $_GET["token"];
    $db = new DB();
    $user = $db->getUserByToken($token);
    $user_id = $user[0]["user_id"];
    echo json_encode($db->getTransportationData($user_id));
}
?>
