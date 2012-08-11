<?php 
require_once("DbHelper.php");
require_once("onlineUser.php");

$db_help = new DB();
refreshOnlineStatus($db_help);
$result = $db_help->getOnlineList();
echo json_encode($result);

?>
