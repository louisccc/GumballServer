<?php
require_once("Dbhelper.php");

$db = new DB();
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $db->updateFeedbackStatusBy($id);
}
?>
