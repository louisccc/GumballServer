<?php
require_once("db.php");

$db = new DB();
$result = null;
if(isset($_GET['amount'])){
    $amount = $_GET['amount'];
    $result = $db->getRecentFeedback($amount);
}
if($result != null){
	echo json_encode($result);
}
else{
	echo null;
}
?>
