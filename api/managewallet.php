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
    $response['message'] = "user id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['points'])) {
    $response['success'] = false;
    $response['message'] = "points is Empty";
    print_r(json_encode($response));
    return false;            
}
if (empty($_POST['type'])) {
    $response['success'] = false;
    $response['message'] = "type is Empty";
    print_r(json_encode($response));
    return false;            
}
if (empty($_POST['reason'])) {
    $response['success'] = false;
    $response['message'] = "reason is Empty";
    print_r(json_encode($response));
    return false;            
}
$user_id = $db->escapeString($_POST['user_id']);
$points= $db->escapeString($_POST['points']);
$type= $db->escapeString($_POST['type']);
$reason= $db->escapeString($_POST['reason']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $current_points=$res[0]['points'];
    if($type=='debit'){
        $new_points=$current_points-$points;
    }
    else{
        $new_points=$current_points+$points;
    }
    $datetime = Date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $sql = "UPDATE `users` SET `points`='$new_points' WHERE id=" . $user_id;
    $db->sql($sql);
    $sql = "INSERT INTO `transactions` (user_id,points,balance,type,date,date_created,reason) VALUES('$user_id','$points','$new_points','$type','$date','$datetime','$reason')" ;
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "Transaction Successfully Completed";
}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}
print_r(json_encode($response));
?>
