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
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$account_number = (isset($_POST['account_number']) && !empty(trim($_POST['account_number']))) ? $db->escapeString(trim($_POST['account_number'])) : '';
$ifsc_code = (isset($_POST['ifsc_code']) && !empty(trim($_POST['ifsc_code']))) ? $db->escapeString(trim($_POST['ifsc_code'])) : '';
$holder_name = (isset($_POST['holder_name']) && !empty(trim($_POST['holder_name']))) ? $db->escapeString(trim($_POST['holder_name'])) : '';
$paytm = (isset($_POST['paytm']) && !empty(trim($_POST['paytm']))) ? $db->escapeString(trim($_POST['paytm'])) : '';
$phonepe = (isset($_POST['phonepe']) && !empty(trim($_POST['phonepe']))) ? $db->escapeString(trim($_POST['phonepe'])) : '';

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "UPDATE `users` SET `account_number`='$account_number',`ifsc_code`='$ifsc_code',`holder_name`='$holder_name',`paytm`='$paytm',`phonepe`='$phonepe' WHERE id=" . $user_id;
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Account Details Updated Successfully";
    $response['data'] = $res;

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));




