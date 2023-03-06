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
$sql = "SELECT *,SUM(points) AS points FROM `haruf` WHERE game_type='andar' AND  game_date = '$date' AND game_name = '$game_name' GROUP BY number ORDER BY number DESC";;
$db->sql($sql);
$res1 = $db->getResult();
$num1 = $db->numRows($res1);
$sql = "SELECT *,SUM(points) AS points FROM `haruf` WHERE game_type='bahar' AND  game_date = '$date' AND game_name = '$game_name' GROUP BY number ORDER BY number DESC";;
$db->sql($sql);
$res2 = $db->getResult();
$num2 = $db->numRows($res2);
$row = array();
$row = array_merge($res1, $res2);
$num = count($row);
if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Bids listed Successfully";
    $response['data'] = $row;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Bids Found";
    $response['data'] = $row;
    print_r(json_encode($response));

}
?>