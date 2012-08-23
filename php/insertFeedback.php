<?php
require_once("db.php");


function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
if(isset($_GET['application_id']) && isset($_GET['type'])){
    $app_id = $_GET['application_id'];
    $type = $_GET['type'];
    if(isset($_GET['description'])){
        $description = $_GET['description'];
    }
    else{
        $description = "this is feedback from ".$app_id;
    }
    $db = new DB();
    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $db->insertFeedbackStatusByUserId($user_id, $app_id, $type, $description);
    }
    else if( isset($_GET['device_id']) && !isset($_GET['user_id'])){
        $device_id = $_GET['device_id'];
        $db->insertFeedbackStatusByDeviceId($device_id, $app_id, $type, $description);
    }
    else{
        $coming_ip = getRealIpAddr();
        $db->insertFeedbackStatusBy($coming_ip, $app_id, $type, $description);
    }
}
?>
