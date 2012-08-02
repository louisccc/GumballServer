<?php 
require_once("DbHelper.php");

$db_help = new DB();
$time = time();
$time_check = $time - 2;
//$db_help->updateAndLoginOnlineList(0, $time);
$db_help->refreshOnlineList($time_check);

$result['num_online_user'] = $db_help->getNumberOfOnline();
echo json_encode($result);
?>
