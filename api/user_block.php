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
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['user_status'])) {
    $response['success'] = false;
    $response['message'] = "User Status is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id= $db->escapeString($_POST['user_id']);
$user_status = $db->escapeString($_POST['user_status']);
$sql = "SELECT * FROM users WHERE id ='$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    if($user_status==1){
        $response['success'] = true;
        $response['message'] = "User Unblocked Successfully";
        $sql = "UPDATE users SET user_status = 1 WHERE id = '$user_id'";
        $db->sql($sql);
    }
    if($user_status==2){
        $response['success'] = true;
        $response['message'] = "User Blocked Successfully";
        $sql = "UPDATE users SET user_status = 2 WHERE id = '$user_id'";
        $db->sql($sql);
    }
        $sql = "SELECT * FROM users WHERE id ='$user_id'";
        $db->sql($sql);
        $result = $db->getResult();
        $response['data'] = $result;
        print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
    print_r(json_encode($response));

}

?>