<?php 
require_once("../db.php");

if(isset($_GET["token"]) && isset($_GET["devicd_id"]) && isset($_GET["feedback_id"]){
    $device_id = $_GET["device_id"];
    $token = $_GET["token"];
    $feedback_id = $_GET["feedback_id"];
    $db = new DB();
    $user_status = $db->getUserByToken($token);
    //echo 
    $user_id = $user_status[0]['user_id'];
    $db->assignFeedbackOfDeviceId($feedback_id, $device_id, $user_id);
}

?>
