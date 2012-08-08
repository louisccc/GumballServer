<?php
require_once("DbHelper.php");

$db = new DB();
if(isset($_GET['device_id'])){
    $dev_id = $_GET['device_id'];
    $result = $db->getFeedbackStatusBy($dev_id);
    echo $result;
}
?>
