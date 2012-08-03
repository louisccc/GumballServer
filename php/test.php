<?php
require_once("Dbhelper.php");

$db = new DB();
$db->insertFeedbackStatusBy($_SERVER['REMOTE_ADDR'],0,"positive");
//$db->getDeviceIdByIpAddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['REMOTE_ADDR'];
?>
