<?php
require_once("DbHelper.php");

$db = new DB();
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $db->updateFeedbackStatusBy($id);
}
?>
