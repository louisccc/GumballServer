<?php
require_once("config.php");
try {
  if ($_GET["report_id"] != "" && $_GET["user_id"]) {
    
    $pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback",
		   $account, $password);
    $query = "update problems set status=0, updated_by=".$_GET["user_id"].", updated_at='NOW()' where id=" . $_GET["report_id"];
    $stmt = $pdo->query($query);

    echo $query;
  }
} catch (PDOException $e){
    var_dump($e->getMessage());
}

$pdo = null;

?>
