<?php

require_once("db.php");
$coming_ip = $_SERVER['REMOTE_ADDR'];

if(isset($_GET["user_id"]) && isset($_GET["coordinate_x"]) && isset($_GET["coordinate_y"]) && isset($_GET["title"])){
    $user_id = $_GET["user_id"];
    $coordinate_x = $_GET["coordinate_x"];
    $coordinate_y = $_GET["coordinate_y"];
    $title = mysql_escape_string($_GET["title"]);
    $db_help = new DB();
    if(isset($_GET["category"])){
        $category = $_GET["category"];
        $db_help->insertFixReportByCategory($title, $coordinate_x, $coordinate_y, $user_id, $category);
    }
    else{
        $db_help->insertFixReport($title, $coordinate_x, $coordinate_y, $user_id);
    }
}
?>
