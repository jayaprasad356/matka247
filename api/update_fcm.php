<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');
$db = new Database();
$db->connect();
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "user_id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['fcm_id'])) {
    $response['success'] = false;
    $response['message'] = "FCM ID is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$fcm_id = $db->escapeString($_POST['fcm_id']);
$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "UPDATE users SET `fcm_id`= '$fcm_id' WHERE `id`= '$user_id'";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Profile Updated";
    $response['data'] = $res;
}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
    $response['data'] = $res;

}
print_r(json_encode($response));
   
    
   


?>
