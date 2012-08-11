<?php 
require_once("DbHelper.php");
require_once("onlineUser.php");

$db_help = new DB();
refreshOnlineStatus($db_help);
$result['num_online_user'] = $db_help->getNumberOfOnline();
echo json_encode($result);

?>
