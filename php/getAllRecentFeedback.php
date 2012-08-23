<?php
require_once("db.php");

$db = new DB();
$result = $db->getAllRecentFeedback(10);
if($result != null){
	echo json_encode($result);
}
else{
	echo "no";
}
?>
