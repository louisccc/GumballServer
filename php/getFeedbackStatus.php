<?php
require_once("Dbhelper.php");

$db = new DB();
//$id = $_GET['id'];
//$db->updateFeedbackStatusBy($id);
if(isset($_GET['device_id'])){
    $dev_id = $_GET['device_id'];
    $result = $db->getFeedbackStatusBy($dev_id);
    echo $result;
}
?>
