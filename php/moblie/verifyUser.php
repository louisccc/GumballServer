<?php
require_once("../db.php");
require_once("../onlineUser.php");

if( isset($_GET["account"]) && isset($_GET["password"])){
    $username = $_GET["account"];
    $password = $_GET["password"];
    $db = new DB();
    $result = $db->verifyUser($username, $password);
    if($result!=null){
        userLogin($db, $result["token"], $_SERVER["REMOTE_ADDR"]);
        $result['success'] = 1;
    }else{
        $result['success'] = 0;
    }
    echo json_encode($result);
}

?>
