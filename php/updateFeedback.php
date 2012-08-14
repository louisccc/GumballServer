<?php
require_once("db.php");
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $db = new DB();
    $db->updateFeedbackStatusBy($id);
}
?>
