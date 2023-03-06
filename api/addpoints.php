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
$user_id = $db->escapeString($_POST['user_id']);
$points= $db->escapeString($_POST['points']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $current_points=$res[0]['points'];
    $new_points=$points+$current_points;
    $datetime = Date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $sql = "UPDATE `users` SET `points`='$new_points' WHERE id=" . $user_id;
    $db->sql($sql);
    $sql= "INSERT INTO points (user_id,points,status,date_created) VALUES ('$user_id','$points',1,'$datetime')";
    $db->sql($sql);
    $sql = "INSERT INTO `transactions` (user_id,points,balance,type,date,date_created) VALUES('$user_id','$points','$new_points','deposit','$date','$datetime')" ;
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();


    $response['success'] = true;
    $response['message'] = "Points Added Successfully";
    $response['data'] = $res;

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));
?>
