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
$sql = "SELECT *,users.id AS id FROM users,haruf WHERE users.id = haruf.user_id AND haruf.game_date = '$date' AND haruf.game_name = '$game_name' GROUP BY haruf.user_id ORDER BY users.id DESC";
$db->sql($sql);
$res1 = $db->getResult();
$sql = "SELECT *,users.id AS id FROM users,games WHERE users.id = games.user_id AND games.game_date = '$date' AND games.game_name = '$game_name' GROUP BY games.user_id ORDER BY users.id DESC";
$db->sql($sql);
$res2 = $db->getResult();
$num1 = $db->numRows($res1);
$num2 = $db->numRows($res2);

$sql = "SELECT SUM(points) AS total_points FROM haruf WHERE game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$totalpoints1 = $db->getResult();

$sql = "SELECT SUM(points) AS total_points FROM games WHERE game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$totalpoints2 = $db->getResult();

$totalpoints1 = $totalpoints1[0]['total_points'];
$totalpoints2 = $totalpoints2[0]['total_points'];
$row = array();
$row = array_merge($res1, $res2);
$num = count($row);

$usersCount = count(array_unique(array_column($row, 'user_id'))); 
$userlist = array_unique(array_column($row, 'user_id'));

$sql = "SELECT * FROM users WHERE id IN (" . implode(',', $userlist) . ")";
$db->sql($sql);
$userlist = $db->getResult();
if ($num >= 1) {
    $num = count($row);
    $totalpoints = $totalpoints1 + $totalpoints2;

    $response['success'] = true;
    $response['message'] = "Users listed Successfully";
    $response['total_users'] = $usersCount;
    $response['total_points'] = $totalpoints;
    $response['data'] = $userlist;
    $response['users'] = $userlist;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No User Found";
    $response['data'] = $row;
    print_r(json_encode($response));

}
?>