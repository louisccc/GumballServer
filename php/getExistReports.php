<?php
require_once("DbHelper.php");

$db_help = new DB();
$result = $db_help->getExistReport();
echo json_encode($result);

?>
