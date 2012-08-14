<?php
function login($db, $device_id, $ipaddr){
    $time = time();
    $time_check = $time - 4; //10 mins

    $db->updateAndLoginOnlineList($device_id, $time, $ipaddr);
    $num_user_online = $db->getNumberOfOnline();
    $db->refreshOnlineList($time_check);
}

function refreshOnlineStatus($db){
    
    $time = time();
    $time_check = $time - 2; //10 mins

    $db->refreshOnlineList($time_check);
    
}

?>
