<?php
require_once("config.php");
//$device_id = $_GET["device_id"];
//require_once("onlineUser.php");
require_once("DbHelper.php");

//know device id 
//window scenario

//$db_help = db_help::getInstance();

//$pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback", $account, $password);
$db_help = new DB();

// refresh the user login table now 
$result['num_online_user'] = $db_help->getNumberOfOnline();
echo json_encode($result);
?>
