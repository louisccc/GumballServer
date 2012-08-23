<?php 
require_once("../db.php");
if(isset($_GET["token"])){
	$db = new DB();
	$token = $_GET["token"];
	$user_id = $db->getUserIdByToken($token);

	$result = $db->getTransportationStatus($user_id);
	echo json_encode($result);
}
?>

