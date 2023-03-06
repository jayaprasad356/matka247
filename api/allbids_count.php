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
$sql = "SELECT *,SUM(points) AS points FROM `games` WHERE game_date = '$date' AND game_name = '$game_name' GROUP BY number ORDER BY number DESC";
$db->sql($sql);
$res = $db->getResult();
$sql = "SELECT SUM(points) AS total_points FROM games WHERE game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$totalpoints = $db->getResult();
$sql = "SELECT * FROM `games` WHERE game_date = '$date' AND game_name = '$game_name' GROUP BY user_id ORDER BY number DESC";
$db->sql($sql);
$resuser = $db->getResult();
$num = $db->numRows($resuser);
$totalpoints = $totalpoints[0]['total_points'];
$sql = "SELECT * FROM `games` WHERE game_type = 'jodi' AND game_date = '$date' AND game_name = '$game_name' GROUP BY game_type";
$db->sql($sql);
$jodi = $db->getResult();
$jodi = $db->numRows($jodi);
$sql = "SELECT * FROM `games` WHERE game_type = 'jodi' AND game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$jodi = $db->getResult();
$jodi = $db->numRows($jodi);
$sql = "SELECT * FROM `games` WHERE game_type = 'odd_even' AND game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$odd_even = $db->getResult();
$odd_even = $db->numRows($odd_even);
$sql = "SELECT * FROM `games` WHERE game_type = 'quick_cross' AND game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$quick_cross = $db->getResult();
$quick_cross = $db->numRows($quick_cross);
$sql = "SELECT * FROM `haruf` WHERE game_date = '$date' AND game_name = '$game_name' GROUP BY user_id ORDER BY number DESC";
$db->sql($sql);
$haruf = $db->getResult();
$sql = "SELECT * FROM `haruf` WHERE game_type='andar' AND game_date = '$date' AND game_name = '$game_name' GROUP BY user_id ORDER BY number DESC";
$db->sql($sql);
$haruf1 = $db->getResult();
$harufnum1 = $db->numRows($haruf1);
$sql = "SELECT * FROM `haruf` WHERE game_type='bahar' AND  game_date = '$date' AND game_name = '$game_name' GROUP BY user_id ORDER BY number DESC";
$db->sql($sql);
$haruf2 = $db->getResult();
$harufnum2 = $db->numRows($haruf2);
$harufnum = $harufnum1 + $harufnum2;
$sql = "SELECT SUM(points) AS total_points FROM haruf WHERE game_date = '$date' AND game_name = '$game_name'";
$db->sql($sql);
$haruftotalpoints = $db->getResult();
$haruftotalpoints = $haruftotalpoints[0]['total_points'];
$totalpoints = $totalpoints + $haruftotalpoints;
$row = array();
$row = array_merge($haruf, $res);
$usersCount = count(array_unique(array_column($row, 'user_id'))); 

if ($num >= 1 || $harufnum >= 1) {
    //$num  = $num + $harufnum;
    $sql = "SELECT * FROM `games`,`haruf` WHERE games.game_date = '$date' AND games.game_name = '$game_name' AND haruf.game_date = '$date' AND haruf.game_name = '$game_name' GROUP BY games.user_id,haruf.user_id";
    $db->sql($sql);
    $resuser = $db->getResult();
    $num = $db->numRows($resuser);
    $response['success'] = true;
    $response['message'] = "Users listed Successfully";
    $response['total_users'] = $usersCount;
    $response['jodi'] = $jodi;
    $response['odd_even'] = $odd_even;
    $response['quick_cross'] = $quick_cross;
    $response['haruf'] = $harufnum;
    $response['total_points'] = $totalpoints;

    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Bid Found";
    $response['data'] = $res;
    print_r(json_encode($response));

}
?>