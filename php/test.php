<?php
require_once("db.php");

$db = new DB();
echo $db->getNumberOfOnlineUser();
//echo $db->getMaxTimeStamp();
#$db->insertFeedbackStatusByDeviceId(2, 0, "positive");
//$db->getDeviceIdByIpAddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['REMOTE_ADDR'];
?>
