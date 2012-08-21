<?php
require_once("db.php");

$db = new DB();
$allDevice = $db->getAllDevice();
$result = Array();
foreach($allDevice as $row){
    $device_id = $row['device_id'];
    if($db->checkIsDeviceOnline($device_id)){
        $newest_data = $db->getNewestDataOf($device_id);

        $log_id = $newest_data[0]['log_id'];

        $row_window = $db->getWindowStateBy($log_id);
        if($row_window != null){
            $newest_data[0] = array_merge($newest_data[0], $row_window[0]);
        }
        $result[$device_id] = $newest_data[0];
    }
}
if(sizeof($result) > 0){
    echo json_encode($result);
}
else{
    echo null;
}

?>
