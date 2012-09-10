<?php

require_once("db.php");
require_once("feedback_type.php");
$coming_up = $_SERVER['REMOTE_ADDR'];

if (isset($_GET["report_id"]) && isset($_GET["user_id"])) {
    $db_help = new DB();
    $report_id = $_GET["report_id"];
    $user_id = $_GET["user_id"];
    $ret = array();
    if($db_help->requestFixReport($report_id)){
        $db_help->updateFixReport($user_id, $report_id);
        $ret['success'] = 1;
        echo json_encode($ret);
        
    }
    else{
        $force = 0;
        if(isset($_GET['force'])){
            $force = $_GET['force'];
        }
        if($force == 1){
            $db_help->updateFixReport($user_id, $report_id);
            $ret['success'] = 1;
            echo json_encode($ret);
        }
        else{
            #$ret = array();
            #$ret['reconfirm'] = 1;
            $db_help->updateFixReport($user_id, $report_id);
            $ret['success'] = 1;
            echo json_encode($ret);
        }
    }
}

?>
