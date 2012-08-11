<?php 
require_once("DbHelper.php");
require_once("onlineUser.php");

$db_help = new DB();
refreshOnlineStatus($db_help);
$resul = $db_help->getOnlineList();
echo json_encode($result);

?>
