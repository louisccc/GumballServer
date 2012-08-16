<?php
require_once("../db.php");
require_once("../onlineUser.php");


if(isset($_GET["token"]) && isset($_GET["trip_id"])){
    $token = $_GET["token"];
    $trip_id = $_GET["trip_id"];
    $db = new DB();
    $user_id = $db->getUserIdByToken($token);
    echo json_encode($db->getTransportationDataByTrip($user_id, $trip_id));
}
?>
