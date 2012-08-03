<?php
require_once("Dbhelper.php");
if(isset($_GET['application_id'])){
    $app_id = $_GET['application_id'];
    $db = new DB();
    $db->insertFeedbackStatusBy($_SERVER['REMOTE_ADDR'], $app_id,"positive");
}
?>
