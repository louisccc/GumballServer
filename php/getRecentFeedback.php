<?php
require_once("db.php");

$db = new DB();
if(isset($_GET['token'])){
    $token = $_GET['token'];
    $user_id = $db->getUserIdByToken($token);
    $result = $db->getRecentFeedbackBy($user_id, 10);
    if($result != null){
        echo json_encode($result);
    }
    else{
        echo null;
    }
}
else{
    echo "no";
}
?>
