<?php
require_once("db.php");

$db = new DB();
if( isset($_GET["account"]) && isset($_GET["password"])){
    $username = $_GET["account"];
    $password = $_GET["password"];
    $db = new DB();
    $result = $db->verifyUser($username, $password);
    echo json_encode($result);
}

//echo $db->getNumberOfOnlineUser();
//echo $db->getMaxTimeStamp();
#$db->insertFeedbackStatusByDeviceId(2, 0, "positive");
//$db->getDeviceIdByIpAddr($_SERVER['REMOTE_ADDR']);
//echo $_SERVER['REMOTE_ADDR'];
?>
