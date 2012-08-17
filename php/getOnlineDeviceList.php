<?php 
require_once("db.php");
require_once("onlineUser.php");

$db = new DB();
refreshOnlineStatus($db);
$result = $db->getOnlineDeviceList();
if($result != null){
    echo json_encode($result);
}
else{
    return null;
}

?>
