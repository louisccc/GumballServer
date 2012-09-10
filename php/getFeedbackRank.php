<?php 
require_once("db.php");
#if(isset($_GET["token"])){
#    $token = $_GET["token"];
    $db = new DB();
    $db->getFeedbackRanking();
#}
?>
