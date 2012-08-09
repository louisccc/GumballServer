<?php
require_once("DbHelper.php");
if(isset($_GET['application_id'])){
    $app_id = $_GET['application_id'];
    $type = $_GET['type'];
    $db = new DB();
    if($type == "positive"){
        $db->insertFeedbackStatusBy($_SERVER['REMOTE_ADDR'], $app_id,"positive");
    }
    else if($type == "negative"){
        $db->insertFeedbackStatusBy($_SERVER['REMOTE_ADDR'], $app_id,"negative");
    }
}
?>
