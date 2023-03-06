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
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$amount= $db->escapeString($_POST['amount']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    if($amount >= 100){
        $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
        $points=$res[0]['points'];
        if($points>=$amount){
            $datetime = Date('Y-m-d H:i:s');
            $date = date('Y-m-d');
            $new_earn=$points-$amount;
            $sql = "UPDATE `users` SET `points`='$new_earn' WHERE id=" . $user_id;
            $db->sql($sql);
            $sql = "INSERT INTO withdrawal  (user_id,points,status,date,date_created) VALUES ('$user_id','$amount',0,'$date','$datetime')";
            $db->sql($sql);
            $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
            $db->sql($sql);
            $res = $db->getResult();
            $datetime = Date('Y-m-d H:i:s');
            $date = date('Y-m-d');
            $sql = "INSERT INTO `transactions` (user_id,points,balance,type,date,date_created) VALUES('$user_id','$amount','$new_earn','withdrawal','$date','$datetime')" ;
            $db->sql($sql);
            $response['success'] = true;
            $response['message'] = "Amount withdrawed Requested  Successfully";
            $response['data'] = $res;
        }
        else{
            $response['success'] = false;
            $response['message'] = "Insufficient Fund";
    
        }
        
    }else{
        $response['success'] = false;
        $response['message'] = "Minimum Withdrawal is 100";       
    }


}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));

