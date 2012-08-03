<?php
require_once("DbHelper.php");
try {
  if ($_GET["report_id"] != "" && $_GET["user_id"]) {
      $db_help = new DB();
      $report_id = $_GET["report_id"];
      $user_id = $_GET["user_id"];
      $db_help->updateFixReport($user_id, $report_id);
      $db_help->insertFeedbackStatusBy($_SERVER['REMOTE_ADDR'], 1, "positive");
  }
} catch (PDOException $e){
    var_dump($e->getMessage());
}

$pdo = null;

?>
