<?php
require_once("../db.php");


if( isset($_GET["account"]) && isset($_GET["password"])){
    $username = $_GET["account"];
    $password = $_GET["password"];
    $db = new DB();
    $db->insertNewUser($username, $password);
    echo json_encode($db->verifyUser($username, $password));
}

?>
