<?php
require_once("DbHelper.php");

try {
    $db_help = new DB();
    $result = $db_help->getExistReport();
    echo json_encode($result);
} catch (PDOException $e){
    var_dump($e->getMessage());
}

$pdo = null;

?>
