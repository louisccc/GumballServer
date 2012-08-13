<?php
require_once("db.php");

$db = new DB();
$result = $db->getExistReport();
if($result != null){
    echo json_encode($result);
}
else{
    echo null;
}
?>
