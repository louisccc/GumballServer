<?php

require_once("db.php");
require_once("feedback_type.php");
$coming_up = $_SERVER['REMOTE_ADDR'];

if (isset($_GET["report_id"]) && isset($_GET["user_id"])) {
    $db_help = new DB();
    $report_id = $_GET["report_id"];
    $user_id = $_GET["user_id"];
    $db_help->updateFixReport($user_id, $report_id);
    #$db_help->insertFeedbackStatusBy($coming_up, feedbackType::$makeFixReport, "positive");
}

?>
