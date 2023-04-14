<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');


$current_timestamp = time();
$response['current_timestamp'] = $current_timestamp;
$response['success'] = true;
$response['message'] = "Timestamp Successfully";
print_r(json_encode($response));
?>