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
if (empty($_POST['withdrawal_id'])) {
    $response['success'] = false;
    $response['message'] = "Withdrawal Id is Empty";
    print_r(json_encode($response));
    return false;
}
$withdrawal_id = $db->escapeString($_POST['withdrawal_id']);
$status = $db->escapeString($_POST['status']);
$sql = "SELECT * FROM withdrawal WHERE id = '" . $withdrawal_id . "'";
$db->sql($sql);
$res = $db->getResult();
if($status == 1){
    $user_id = $res[0]['user_id'];
    $points = $res[0]['points'];
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res1 = $db->getResult();
    $currentpoints=$res1[0]['points'];
    $new_points=$currentpoints-$points;
    // $sql = "UPDATE `users` SET `points`='$new_points' WHERE id=" . $user_id;
    // $db->sql($sql);

    $sql = "UPDATE `withdrawal` SET `status`='$status' WHERE id = " . $withdrawal_id;
    $db->sql($sql);
    $res = $db->getResult();
}
else{
    $sql = "UPDATE `withdrawal` SET `status`='$status' WHERE id = " . $withdrawal_id;
    $db->sql($sql);
    $res = $db->getResult();

}

$response['success'] = true;
$response['message'] = "Updated Successfully";
print_r(json_encode($response));
?>