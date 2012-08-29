<?php
session_start();
session_destroy();
$result['success'] = 1;
echo json_encode($result);
?>
