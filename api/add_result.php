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
require_once '../includes/functions.php';
require_once('../includes/firebase.php');
require_once ('../includes/push.php');

$db = new Database();
$db->connect();
$fnc = new functions;
include_once('../includes/custom-functions.php');
    
$fn = new custom_functions;

if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['day'])) {
    $response['success'] = false;
    $response['message'] = "Day is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['month'])) {
    $response['success'] = false;
    $response['message'] = "Month is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['year'])) {
    $response['success'] = false;
    $response['message'] = "Year is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['game_name'])) {
    $response['success'] = false;
    $response['message'] = "Game Name is Empty";
    print_r(json_encode($response));
    return false;
}
$game_date = $db->escapeString($_POST['date']);
$result = (isset($_POST['result']) && $_POST['result'] != "") ? $db->escapeString($_POST['result']) : "0";
$result = ltrim($result, '0') ?: '0'; 
$day = $db->escapeString($_POST['day']);
$month = $db->escapeString($_POST['month']);
$year = $db->escapeString($_POST['year']);
$game_name = $db->escapeString($_POST['game_name']);

// $sql = "INSERT INTO winners (user_id, points, game_name,date)
// SELECT id, points, game_name, game_date FROM games WHERE game_date = '$date' AND game_name = '$game_name' AND number = '$result'";
$sql = "SELECT * FROM games WHERE game_date = '$game_date' AND game_name = '$game_name' AND number = '$result'";
$db->sql($sql);
$res = $db->getResult();
$response['game_res'] = $res;
$response['games_sql'] = $sql;
foreach ($res as $row) {
    $points = $row['points'] * 93;
    $user_id = $row['user_id'];
    $game_type = $row['game_type'];
    $sql = "INSERT INTO winners (user_id, points, game_name,game_type,date,result) VALUES ('$row[user_id]', '$points', '$game_name', 'games','$game_date','$result')";
    $db->sql($sql);
    $sql = "UPDATE users SET points = points + '$points' WHERE id = '$row[user_id]'";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '$row[user_id]'";
    $db->sql($sql);
    $res = $db->getResult();
    $update_user_points = $res[0]['points'];
    $datetime = Date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,game_type,date,date_created) VALUES('$user_id','$points','$update_user_points','correctresult','$game_name','$game_type','$date','$datetime')" ;
    $db->sql($sql);
}
$harufresult = sprintf("%02d", $result);
$andarresult = substr($harufresult, 0, 1); 
$baharresult = substr($harufresult, 1, 2); 
$sql = "SELECT * FROM haruf WHERE game_date = '$game_date' AND game_name = '$game_name' AND number = '$andarresult' AND game_type = 'andar'";
$db->sql($sql);
$res = $db->getResult();
$response['haruf_andar_res'] = $res;
foreach ($res as $row) {
    $points = round($row['points'] * 9.2);
    $user_id = $row['user_id'];
    $sql = "INSERT INTO winners (user_id, points, game_name,game_type,date,result) VALUES ('$user_id', '$points', '$game_name','andar','$game_date','$result')";
    $db->sql($sql);
    $sql = "UPDATE users SET points = points + '$points' WHERE id = '$row[user_id]'";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '$row[user_id]'";
    $db->sql($sql);
    $res = $db->getResult();
    $update_user_points = $res[0]['points'];
    $datetime = Date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,game_type,reason,date,date_created) VALUES('$user_id','$points','$update_user_points','correctresult','$game_name','andar','andar','$date','$datetime')" ;
    $db->sql($sql);

}
$sql = "SELECT * FROM haruf WHERE game_date = '$game_date' AND game_name = '$game_name' AND number = '$baharresult' AND game_type = 'bahar'";
$db->sql($sql);
$res = $db->getResult();
$response['haruf_bahar_res'] = $res;
foreach ($res as $row) {
    $points = round($row['points'] * 9.2);
    $user_id = $row['user_id'];
    $sql = "INSERT INTO winners (user_id, points, game_name,game_type,date,result) VALUES ('$row[user_id]', '$points', '$game_name','bahar', '$game_date','$result')";
    $db->sql($sql);
    $sql = "UPDATE users SET points = points + '$points' WHERE id = '$row[user_id]'";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '$row[user_id]'";
    $db->sql($sql);
    $res = $db->getResult();
    $update_user_points = $res[0]['points'];
    $datetime = Date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,game_type,reason,date,date_created) VALUES('$user_id','$points','$update_user_points','correctresult','$game_name','bahar','bahar','$date','$datetime')" ;
    $db->sql($sql);

}
$sql = "INSERT INTO results (date, result, day, month, year, game_name) VALUES ('$game_date', '$result', '$day', '$month', '$year', '$game_name')";
$db->sql($sql);

$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$url .= $_SERVER['SERVER_NAME'];
$url .= $_SERVER['REQUEST_URI'];
$server_url = dirname($url).'/';
$id = "0";
$type = "default";

$push = null;

//first check if the push has an image with it
$push = new Push(
    'Result For Game '.$game_name,
    $result.' is the Winning Number',
    null,
    $type,
    $id
);

//getting the push from push object
$mPushNotification = $push->getPush();

//getting the token from database object 
$devicetoken = $fnc->getAllTokens();
//$devicetoken1 = $fnc->getAllTokens("devices");
//$final_tokens = array_merge($devicetoken,$devicetoken1);
$f_tokens = array_unique($devicetoken);
$devicetoken_chunks = array_chunk($f_tokens,1000);
foreach($devicetoken_chunks as $devicetokens){
    //creating firebase class object 
    $firebase = new Firebase(); 

    //sending push notification and displaying result 
    $firebase->send($devicetokens, $mPushNotification);
}
$response['success'] = true;
$response['message'] = "Result Announced Successfully";
print_r(json_encode($response));
?>