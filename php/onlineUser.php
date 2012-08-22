<?php
function login($db, $device_id, $ipaddr){
    $time = time();
    $time_check = $time - 10; //10 mins

    $db->updateAndLoginOnlineDeviceList($device_id, $time, $ipaddr);
    $db->refreshOnlineDeviceList($time_check);
}

function refreshOnlineStatus($db){
    $time = time();
    $time_check = $time - 10; //10 mins
    $db->refreshOnlineDeviceList($time_check);
}

function userLogin($db, $token, $ipaddr){
    $time = time();
    $time_check = $time - 1800;
    $db->updateAndLoginOnlineUserList($token, $time, $ipaddr);
    $db->refreshOnlineUserList($time_check);
    
}
function refreshOnlineUserStatus($db){
    $time = time();
    $time_check = $time - 1800; //10 mins
    $db->refreshOnlineUserList($time_check);
}

?>
