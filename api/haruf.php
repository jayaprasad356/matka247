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

include_once('../includes/variables.php');
$db = new Database();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['game_name'])) {
    $response['success'] = false;
    $response['message'] = "Game name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['game_type'])) {
    $response['success'] = false;
    $response['message'] = "Game type is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['game_method'])) {
    $response['success'] = false;
    $response['message'] = "Game method is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['number'])) {
    $response['success'] = false;
    $response['message'] = "Number is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['points'])) {
    $response['success'] = false;
    $response['message'] = "Points is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['total_points'])) {
    $response['success'] = false;
    $response['message'] = "Total Points is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);
$game_name = $db->escapeString($_POST['game_name']);
$game_type = $db->escapeString($_POST['game_type']);
$game_method = $db->escapeString($_POST['game_method']);
$total_points = $db->escapeString($_POST['total_points']);
$number = $_POST['number'];
$points = $_POST['points'];
$points_arr = json_decode($points, true);
$number_arr = json_decode($number, true);
$game_date = date('Y-m-d');

if($game_name =='FD'){
    $game_time = '05:45 PM';
}
else if($game_name =='GB'){
    $game_time = '07:45 PM';
}
else if($game_name =='GL'){
    $game_time = '10:45 PM';
}
else if($game_name =='DS'){
    $game_time = '02:20 AM';
}

$start_date = $game_date .' '.$game_time;
$start_date = new DateTime($start_date);
$now = new DateTime();
$now  = $now->format('Y-m-d h:i A');
$end_date = new DateTime($now);

if ($start_date < $end_date) {
    //$game_date = date('Y-m-d', strtotime($game_date . ' +1 day'));
    $response['success'] = false;
    $response['message'] = "Please Bet Next Day";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $current_points=$res[0]['points'];
    if($current_points>=$total_points){
        $new_points=$current_points-$total_points;
        $sql = "UPDATE `users` SET `points`='$new_points' WHERE id=" . $user_id;
        $db->sql($sql);
        $datetime = Date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $sql = "INSERT INTO `transactions` (user_id,game_name,game_type,game_method,points,balance,type,date,date_created) VALUES('$user_id','$game_name'
        ,'$game_type','$game_method','$total_points','$new_points','game','$date','$datetime')" ;
        $db->sql($sql);
        for ($i = 0; $i < count($points_arr); $i++) {
            $p = $points_arr[$i] % 5;
            if($points_arr[$i] > 0 && $p == 0){
                $sql = "SELECT * FROM `haruf` WHERE `game_name`='$game_name' AND `game_type`='$game_type' AND `game_date`='$game_date' AND `number`='$number_arr[$i]' AND `user_id`='$user_id'";
                $db->sql($sql);
                $resp = $db->getResult();
                $num = $db->numRows($resp);
                if ($num == 1) {
                    $points_arr[$i] = $points_arr[$i] + $resp[0]['points'];
                    $game_id = $resp[0]['id'];
                    $sql = "UPDATE `haruf` SET `points`='$points_arr[$i]' WHERE id = '$game_id'";
                    $db->sql($sql);

                }
                else{
                    $sql = "INSERT INTO `haruf`  (user_id,game_name,game_type,game_date,number,points) VALUES('$user_id','$game_name'
                    ,'$game_type','$game_date','$number_arr[$i]','$points_arr[$i]')" ;
                    $db->sql($sql);

                }


            }
        }
        $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
        $db->sql($sql);
        $res = $db->getResult();
    
        $response['success'] = true;
        $response['message'] = "Bet Placed Successfully";
        $response['data'] = $res;

    }
    else{
        $response['success'] = false;
        $response['message'] = "Insufficient points";

    }


     
}
else{
    $response['success'] = false;
    $response['message'] = "Game Not Found";

}

print_r(json_encode($response));
?>




