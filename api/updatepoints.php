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

if (empty($_POST['status'])) {
    $response['success'] = false;
    $response['message'] = "Status is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['point_id'])) {
    $response['success'] = false;
    $response['message'] = "Point Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "user id is Empty";
    print_r(json_encode($response));
    return false;
}
$point_id = $db->escapeString($_POST['point_id']);
$user_id = $db->escapeString($_POST['user_id']);
$status = $db->escapeString($_POST['status']);
if($status == 1){
    $sql = "SELECT * FROM points WHERE id = " . $point_id;
    $db->sql($sql);
    $resp = $db->getResult();
    $points = $resp[0]['points'];

    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $current_points=$res[0]['points'];
    $new_points=$points+$current_points;
    $datetime = Date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $sql = "UPDATE `users` SET `points`='$new_points' WHERE id=" . $user_id;
    $db->sql($sql);
    $date = Date('Y-m-d H:i:s');
    $sql = "INSERT INTO `transactions` (user_id,points,balance,type,date,date_created) VALUES('$user_id','$current_points','$new_points','deposit','$date','$datetime')" ;
    $db->sql($sql);
}
$sql = "UPDATE `points` SET `status`='$status' WHERE id = " . $point_id;
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['message'] = "Updated Successfully";
print_r(json_encode($response));
?>