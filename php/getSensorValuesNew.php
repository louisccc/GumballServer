<?php
require_once("config.php");
try {
	$pdo = new PDO("mysql:host=localhost; dbname=sweetfeedback", $account, $password);

	$results;
	for ($index = 0; $index < 5; $index++){
		// SELECT *  FROM sensor_log WHERE device_id = 1 ORDER BY created_at DESC limit 1;

		$stmt = $pdo->query("SELECT * FROM basic_sensor_log WHERE device_id = ".$index." ORDER BY created_time DESC limit 1");

		$i = 0;
		if($stmt != ''){
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$result[$i] = $row;
				$i++;	
			}
		}
		if( $i == 0 ){
			$result[0] = array("no_data" => "true");
		}
		if(!array_key_exists("no_data", $result[0])){
			$log_id = $result[0]["log_id"];
			$stmt = $pdo->query("SELECT * FROM window_state_log_ext WHERE log_id = ".$log_id." limit 1");
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$result[0] = array_merge($result[0], $row);
			}
		}
		$results[$index] = $result;
	}
	echo json_encode($results);
} catch (PDOException $e){
	var_dump($e->getMessage());
}

$pdo = null;

?>
