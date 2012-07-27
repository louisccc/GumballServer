<?php
require_once "config.php";

try {
    $pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback",
                   $account, $password);
    $stmt = $pdo->query("SELECT id, title, coordinate_x, coordinate_y, created_by,  created_at  FROM problems WHERE status = 1");

    $i = 0;
    $results;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
         $result[$i] = $row;
	 $i++;	
    }
    echo json_encode($result);
} catch (PDOException $e){
    var_dump($e->getMessage());
}

$pdo = null;

?>
