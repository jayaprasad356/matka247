<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

include_once('../includes/variables.php');
$db = new Database();
$db->connect();

if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
    print_r(json_encode($response));
    return false;
}

$mobile = $db->escapeString($_POST['mobile']);
$name = $db->escapeString($_POST['name']);
$device_id = $db->escapeString($_POST['device_id']);
$datetime = Date('Y-m-d H:i:s');
$sql= "INSERT INTO users (mobile,name,points,loggedin,device_id,user_status,date_created) VALUES ('$mobile','$name',0,1,'$device_id',1,'$datetime')";
$db->sql($sql);
$res = $db->getResult();
$sql = "SELECT * FROM users WHERE mobile ='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
$response['success'] = true;
$response['message'] = "Logged In Successfully";
$response['data'] = $res;

print_r(json_encode($response));




?>