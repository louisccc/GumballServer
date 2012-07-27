<?php
require_once("config.php");
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
    $pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback",
		   $account, $password);
    $query = "insert into problems values( NULL, '".$title. "','" .$title. "'," .$coordinate_x. "," .$coordinate_y. "," .$user_id. ", 1, NOW(), NOW()," .$user_id. ");";
    $stmt = $pdo->query($query);

    echo $query;
  }
} catch (PDOException $e){
    var_dump($e->getMessage());
}

$pdo = null;

?>
