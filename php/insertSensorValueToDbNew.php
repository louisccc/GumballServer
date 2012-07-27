<?php
require_once("config.php");

$sound_level = $_GET["sound_level"];;
$light_level = $_GET["light_level"];
$temperature = $_GET["temperature"];
$people_presence = $_GET["people_presence"];
$window_state = $_GET["window_state"];
$device_id = $_GET["device_id"];

require_once("onlineUser.php");




//echo 'data insert '.$sound_level.' '.$light_level.' '.$temperature.' '.$people_presence.' '.$window_state.' '.$device_id;
$pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback", $account, $password);

$query = "select * from basic_sensor_log WHERE device_id = '$device_id' order by created_time DESC limit 1";
$stmt = $pdo->query($query);
$data_set = 0;
if( $stmt->rowCount() > 0){
	$data_set = 1;
	$result = null;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$result = $row;
	}
	$log_id = $result["log_id"];
	$stmt = $pdo->query("SELECT * FROM window_state_log_ext WHERE log_id = ".$log_id." limit 1");
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$result = array_merge($result, $row);
	}
}

$query = "select * from user_online";
$stmt = $pdo->query($query);
$num_online = $stmt->rowCount();

//echo $num_online;
if(isset($sound_level) && isset($light_level) && isset($temperature)){
	if( $data_set == 1 && $num_online == 1 && $result['window_state'] == '1' && $window_state == '0'){
		//print_r($result);
		echo "hey you need to get feedback";
	}
	$query = "insert into basic_sensor_log values( NULL,  " . $device_id .",". $light_level.",".$temperature.",".$sound_level.", NOW())";
	$stmt = $pdo->query($query);

	$insert_id = $pdo->lastInsertId();
	if(isset($people_presence)){
		$query = "insert into window_state_log_ext values(".$insert_id.",".$window_state.")";
		$stmt = $pdo->query($query);
	}

	if(isset($window_state)){
		$query = "insert into people_presence_log_ext values(".$insert_id.",".$people_presence.")";
		$stmt = $pdo->query($query);
	}
}


$pdo = null;

?>
