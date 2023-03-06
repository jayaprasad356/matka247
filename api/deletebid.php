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
$user_id = $db->escapeString($_POST['user_id']);
$sql = "SELECT * FROM games WHERE user_id = '$user_id' AND game_name = '$game_name' AND game_date = '$date'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
$sql = "SELECT SUM(points) AS total_points FROM games WHERE user_id = '$user_id' AND game_name = '$game_name' AND game_date = '$date'";
$db->sql($sql);
$resg = $db->getResult();
$sql = "SELECT SUM(points) AS total_points FROM haruf WHERE user_id = '$user_id' AND game_name = '$game_name' AND game_date = '$date'";
$db->sql($sql);
$resh = $db->getResult();
$totalpoints = $resg[0]['total_points'] + $resh[0]['total_points'];
$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$points = $res[0]['points'];
$newpoints = $points + $totalpoints;
$sql = "UPDATE users SET points = '" . $newpoints . "' WHERE id = '" . $user_id . "'";
$db->sql($sql);
$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$sql = "DELETE FROM games WHERE user_id = '$user_id' AND game_name = '$game_name' AND game_date = '$date'";
$db->sql($sql);
$sql = "DELETE FROM haruf WHERE user_id = '$user_id' AND game_name = '$game_name' AND game_date = '$date'";
$db->sql($sql);
$datetime = Date('Y-m-d H:i:s');
$date = date('Y-m-d');
$sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,date,date_created) VALUES('$user_id','$totalpoints','$newpoints','delete_bids','$game_name','$date','$datetime')" ;
$db->sql($sql);
$response['success'] = true;
$response['message'] = "Bids Deleted Successfully";
$response['points'] = $points;
$response['totalpoints'] = $totalpoints;
$response['data'] = $res;
print_r(json_encode($response));

?>