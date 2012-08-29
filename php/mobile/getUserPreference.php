<?php
require_once("../db.php");
$db= new DB();
if(isset($_GET['token'])){
    $token = $_GET['token'];
    $user_id = $db->getUserIdByToken($token);
    if($user_id != null){
        $result = $db->getUserPreference($user_id);
        if($result != null){
            echo json_encode($result);
        }
    }
}
?>
