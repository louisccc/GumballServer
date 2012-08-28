<?php

require_once("db.php");
require_once("feedback_type.php");
$coming_up = $_SERVER['REMOTE_ADDR'];

if (isset($_GET["report_id"]) && isset($_GET["user_id"])) {
    $db_help = new DB();
    $report_id = $_GET["report_id"];
    $user_id = $_GET["user_id"];
    if($db_help->requestFixReport($report_id)){
        $db_help->updateFixReport($user_id, $report_id); 
    }
    else{
        $force = 0;
        if(isset($_GET['force'])){
            $force = $_GET['force'];
        }
        if($force == 1){
            $db_help->updateFixReport($user_id, $report_id);
        }
        else{
            $ret = array();
            $ret['reconfirm'] = 1;
            echo json_encode($ret);
        }
    }
    #$db_help->insertFeedbackStatusBy($coming_up, feedbackType::$makeFixReport, "positive");
}

?>
