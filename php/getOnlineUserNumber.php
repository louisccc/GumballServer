<?php 
require_once("config.php");
//require_once("onlineUser.php");
require_once("DbHelper.php");

$db_help = new DB();
$result['num_online_user'] = $db_help->getNumberOfOnline();
echo json_encode($result);
?>
