<?php
require_once("../db.php");
$db = new DB();
$db->insertFeedbackStatusByUserId(2, 20, "positive");

?>
