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
$sql = "SELECT *,SUM(points) AS points FROM `games` WHERE game_date = '$date' AND game_name = '$game_name' GROUP BY number ORDER BY number DESC";;
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Bids listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Bids Found";
    $response['data'] = $res;
    print_r(json_encode($response));

}
?>