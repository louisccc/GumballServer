<?php
require_once("db.php");
$coming_ip = $_SERVER['REMOTE_ADDR'];

if(isset($_GET['application_id'])){
    $app_id = $_GET['application_id'];
    $type = $_GET['type'];
    $db = new DB();
    if($type == "negative"){
        $db->insertFeedbackStatusBy($coming_ip, $app_id,"negative");
    }
    else{
        $db->insertFeedbackStatusBy($coming_ip, $app_id,"positive");
    }
}
?>
