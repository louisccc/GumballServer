<?php
session_start();
require_once("../db.php");
require_once("../onlineUser.php");

$inactive = 3*24*60*60; // 3 days

if(isset($_SESSION['is_logged_in']) && ($_SESSION['is_logged_in'] == 1)){
    $session_life = time() - $_SESSION['create_time'];

    if($session_life > $inactive){
        session_destroy();
        $result['is_logged_in'] = 0;
    }else{
        $result['is_logged_in'] = 1;
        $result['account'] = $_SESSION['account'];
        $result['token'] = $_SESSION['token'];
        $result['user_id'] = $_SESSION['user_id'];
    }
    echo json_encode($result);
}else{

    //login
    if( isset($_GET["account"]) && isset($_GET["password"])){
        $username = $_GET["account"];
        $password = $_GET["password"];
        $db = new DB();
        $result = $db->verifyUser($username, $password);

        if($result!=null){
            userLogin($db, $result["token"], $_SERVER["REMOTE_ADDR"]);
            $result['success'] = 1;
            $result['is_logged_in'] = 1;
            
            $_SESSION['is_logged_in'] = 1;
            $_SESSION['account'] = $username;
            $_SESSION['token'] = $result["token"];
            $_SESSION['user_id'] = $result["user_id"];
            $_SESSION['create_time'] = time();

        }else{
            $result['success'] = 0;
            $result['is_logged_in'] = 0;
            $_SESSION['is_logged_in'] = 0;
        }
    }else{
        $result['success'] = 0;
        $result['is_logged_in'] = 0;
        $_SESSION['is_logged_in'] = 0;
    }
    echo json_encode($result);
}

?>
