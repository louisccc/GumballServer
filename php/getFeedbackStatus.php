<?php
require_once("DbHelper.php");

if(isset($_GET['device_id'])){
    $db = new DB();
    $device_id = $_GET['device_id'];
    $result = $db->getFeedbackStatusBy($device_id);
    if($result != null){
        echo json_encode($result);
    }
}
?>
