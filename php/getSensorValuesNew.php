<?php
require_once("DbHelper.php");

try{
    $db = new DB();
    $allDevice = $db->getAllDevice();
    $result = Array();
    foreach($allDevice as $row){
        $device_id = $row['device_id'];
        $row = $db->getNewestDataOf($device_id);
        $log_id = $row[0]['log_id'];
        $row_window = $db->getWindowStateBy($log_id);
        $row_merge = array_merge($row[0], $row_window[0]);
        $result[$device_id] = $row_merge;
    }
    echo json_encode($result);
} catch (PDOException $e){
    var_dump($e->getMessage());
}

?>
