<?php
require_once("Dbhelper.php");

$db = new DB();
//echo $db->getMaxTimeStamp();
$db->insertFeedbackStatusByDeviceId(2, 0, "positive");
//$db->getDeviceIdByIpAddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['REMOTE_ADDR'];
?>
