<?php
require_once("../db.php");
require_once("../onlineUser.php");


if(isset($_GET["token"])){
    $token = $_GET["token"];
    $db = new DB();
    $user_id = $db->getUserIdByToken($token);
    echo json_encode($db->getTripsOfUser($user_id));
}
?>
