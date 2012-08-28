<?php 
require_once("../db.php");
if(isset($_POST["token"])){
    $db = new DB();
    $token = $_POST["token"];
    $user_id = $db->getUserIdByToken($token);

    $trip_id = $_POST["trip_id"];
    $type = $_POST["type"];
    $walking_per = $_POST["walking_percentage"];
    $biking_per = $_POST["biking_percentage"];
    $driving_per = $_POST["driving_percentage"];
    $train_per = $_POST["train_percentage"];
    $average_speed = $_POST["average_speed"];
    $max_speed = $_POST["max_speed"];
    $total_distance = $_POST["total_distance"];
    $total_time = $_POST["total_time"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    
    $db->insertStatusDataRowToData($user_id, $trip_id, $type, $walking_per, $biking_per, $train_per, $driving_per, $average_speed, $max_speed, $total_distance, $total_time, $start_time, $end_time); 
    echo "shit";
}
?>

