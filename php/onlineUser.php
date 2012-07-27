<?php

//require_once("config.php");
require_once("DbHelper.php");
//if(isset($device_id)){

$time = time();
$time_check = $time - 2; //10 mins

//echo $device_id.' '.$time.' '.$time_check."<br>";
$db = new Db();
//$pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback", $account, $password);

// refresh the user login table now 
$db->updateAndLoginOnlineList($device_id, $time);
$num_user_online = $db->getNumberOfOnline();
//echo "User online :".$num_user_online;

// if over 10 minute, delete session 
$db->refreshOnlineList($time_check);

//}
//else{
//	echo "No variable named device_id";
//}

?>
