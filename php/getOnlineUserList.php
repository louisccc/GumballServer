<?php 
require_once("db.php");
require_once("onlineUser.php");

$db = new DB();
refreshOnlineUserStatus($db);
$result = $db->getOnlineUserList();
if($result != null){
    echo json_encode($result);
}
else{
    return null;
}

?>
