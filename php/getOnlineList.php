<?php 
require_once("DbHelper.php");
require_once("onlineUser.php");

$db = new DB();
refreshOnlineStatus($db_help);
$result = $db->getOnlineList();
if($result != null){
    echo json_encode($result);
}
else{
    return null;
}

?>
