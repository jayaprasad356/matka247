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
if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['game_name'])) {
    $response['success'] = false;
    $response['message'] = "Game Name is Empty";
    print_r(json_encode($response));
    return false;
}
$date = $db->escapeString($_POST['date']);
$game_name = $db->escapeString($_POST['game_name']);
$sql = "SELECT * FROM results WHERE date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $id = $res[0]['id'];
    $result = $res[0]['result'];
    $response['success'] = true;
    $response['message'] = "Results listed Successfully";
    $response['result'] = $result;
    $response['id'] = $id;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Results Found";
    print_r(json_encode($response));
}
?>