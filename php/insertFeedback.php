<?php
require_once("db.php");

if(isset($_GET['application_id']) && isset($_GET['type'])){
    $app_id = $_GET['application_id'];
    $type = $_GET['type'];
    $db = new DB();
    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $db->insertFeedbackStatusByUserId($user_id, $app_id, $type);
    }
    else if( isset($_GET['device_id']) && !isset($_GET['user_id'])){
        $device_id = $_GET['device_id'];
        $db->insertFeedbackStatusByDeviceId($device_id, $app_id, $type);
    }
    else{
        $coming_ip = $_SERVER['REMOTE_ADDR'];
        $db->insertFeedbackStatusBy($coming_ip, $app_id, $type);
    }
}
?>
