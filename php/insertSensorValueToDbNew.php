<?php
require_once("db.php");
require_once("onlineUser.php");

$coming_ip = $_SERVER['REMOTE_ADDR'];
if(isset($_GET["d_id"])){

    $device_id = $_GET["d_id"];
    $db_help = new DB();

    login($db_help, $device_id, $coming_ip);
    $row = $db_help->getNewestDataOf($device_id);
    if($row != null){
        $log_id = $row[0]['log_id'];
        $row_window = $db_help->getWindowStateBy($log_id);
        $row_merge = array();
        if($row_window != null){
            $row_merge = array_merge($row[0], $row_window[0]);
        }
        else{
            $row_merge = $row[0];
        }
    }
    $num_online = $db_help->getNumberOfOnlineDevice();

    if(isset($_GET["s_lv"]) && isset($_GET["l_lv"]) && isset($_GET["tem"])){
        $sound_level = $_GET["s_lv"];
        $light_level = $_GET["l_lv"];
        $temperature = $_GET["tem"];
        $people_presence = $_GET["p"];
        $window_state = $_GET["w"];

        if( $row != null && isset($row_merge['window_state']) && $num_online == 1 && $row_merge['window_state'] == '1' && $window_state == '0'){
            $db_help->insertFeedbackStatusByDeviceId($device_id, 3, "positive", "You are the last person who close window!");
        }
        $insert_id = $db_help->insertSensorBasic($device_id, $light_level, $temperature, $sound_level);

        if(isset($people_presence)){
            $db_help->insertWindowState($insert_id, $window_state);
        }
        if(isset($window_state)){
            $db_help->insertPeopleState($insert_id, $people_presence);
        }
    }

}
?>
