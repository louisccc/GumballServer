<?php 
require_once("db.php");
require_once("onlineUser.php");

$db = new DB();
refreshOnlineStatus($db);
$result['num_online_device'] = $db->getNumberOfOnlineDevice();
echo json_encode($result);

?>
