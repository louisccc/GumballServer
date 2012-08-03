<?php
require_once("DbHelper.php");
try {
  $user_id = $_GET["user_id"];
  $coordinate_x = $_GET["coordinate_x"];
  $coordinate_y = $_GET["coordinate_y"];
  $title = mysql_escape_string($_GET["title"]);

  $isOK = 1;
  if ($user_id == "") $isOK = 0;
  else if ($coordinate_x == "") $isOK ==0;
  else if ($coordinate_y == "") $isOK ==0;
  else if ($title == "") $isOK ==0;


  if ($isOK == 1) {
      $db_help = new DB();
      $db_help->insertFixReport($title, $coordinate_x, $coordinate_y, $user_id);
      $db_help->insertFeedbackStatusBy($_SERVER['REMOTE_ADDR'], 2, "positive");
  }
} catch (PDOException $e){
    var_dump($e->getMessage());
}

$pdo = null;

?>
