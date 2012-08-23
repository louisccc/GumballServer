<?php 
require_once("db.php");
if(isset($_POST("token"))){
    $db = new DB();
    $token = $_POST["token"];
    $user_id = $db->getUserIdByToken($token);

    $trip_id = $_POST["trip_id"];
    $type = $_POST["type"];
    $average_speed = $_POST["average_speed"];
    $max_speed = $_POST["max_speed"];
    $total_distance = $_POST["total_distance"];
    $total_time = $_POST["total_time"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    
    $db->insertStatusDataRowToData($user_id, $trip_id, $type, $average_speed, $max_speed, $total_distance, $total_time, $start_time, $end_time); 
    echo "shit";
}
?>

