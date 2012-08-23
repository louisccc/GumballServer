<?php
require_once("db.php");
require_once("onlineUser.php");
$db = new DB();
refreshOnlineStatus($db);
refreshOnlineUserStatus($db);
$allDevice = $db->getAllDevice();
$result['data'] = Array();

if($allDevice != null){
	foreach($allDevice as $row){
		$device_id = $row['device_id'];
		if($db->checkIsDeviceOnline($device_id)){
			$newest_data = $db->getNewestDataOf($device_id);

			$log_id = $newest_data[0]['log_id'];

			$row_window = $db->getWindowStateBy($log_id);
			if($row_window != null){
				$newest_data[0] = array_merge($newest_data[0], $row_window[0]);
			}
			$user_id = $db->getUserIdByIpAddr($_SERVER['REMOTE_ADDR']);
			if($user_id!=null){
				$status = $db->getNewestTransportationStatus($user_id);
				$newest_data[0]['trans'] = $status[0];
			}

			$result['data'][$device_id] = $newest_data[0];
		}
	}
}
echo json_encode($result);

?>
