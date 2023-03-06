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
if (empty($_POST['points'])) {
    $response['success'] = false;
    $response['message'] = "points is Empty";
    print_r(json_encode($response));
    return false;            
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;            
}
$user_id = $db->escapeString($_POST['user_id']);
$points= $db->escapeString($_POST['points']);
$mobile= $db->escapeString($_POST['mobile']);
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $user_points=$res[0]['points'];
    if($user_points>=$points){
        $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
        $db->sql($sql);
        $resshare = $db->getResult();

        $num = $db->numRows($resshare);
        if ($num == 1) {
            $share_user_points=$resshare[0]['points'];
            $shared_user_id=$resshare[0]['id'];
            $update_user_points=$user_points-$points;
            $update_share_user_points=$share_user_points+$points;
            $datetime = Date('Y-m-d H:i:s');
            $date = date('Y-m-d');
            $sql = "INSERT INTO `share`(`user_id`, `shared_user_id`, `points`, `date`, `date_created`) VALUES ('$user_id','$shared_user_id','$points','$date','$datetime')";
            $db->sql($sql);
            $sql = "UPDATE `users` SET `points`='$update_user_points' WHERE id=" . $user_id;
            $db->sql($sql);
            $sql = "UPDATE `users` SET `points`='$update_share_user_points' WHERE mobile=" . $mobile;
            $db->sql($sql);
            $sql = "SELECT * FROM users WHERE id = '$user_id'";
            $db->sql($sql);
            $res = $db->getResult();
            $mymobile=$res[0]['mobile'];
            $datetime = Date('Y-m-d H:i:s');
            $date = date('Y-m-d');
            $sql = "INSERT INTO `transactions` (user_id,points,balance,type,share_to,date,date_created) VALUES('$user_id','$points','$update_user_points','sharepoints','$mobile','$date','$datetime')" ;
            $db->sql($sql);
            $sql = "INSERT INTO `transactions` (user_id,points,balance,type,share_from,date,date_created) VALUES('$shared_user_id','$points','$update_share_user_points','sharepoints','$mymobile','$date','$datetime')" ;
            $db->sql($sql);
            $response['success'] = true;
            $response['message'] = "Shared Points Successfully";
            $response['data'] = $res;
            print_r(json_encode($response));
            return false;
        }
        else{
            $response['success'] = false;
            $response['message'] = "Share Mobile Number Not a User";
            print_r(json_encode($response));
            return false;
        }

    }
    else{
        $response['success'] = false;
        $response['message'] = "Insufficient Fund";
        print_r(json_encode($response));
        return false;
    }
    $new_points=$points+$current_points;
    $sql= "INSERT INTO points (user_id,points) VALUES ('$user_id','$points')";
    $db->sql($sql);
    $sql = "UPDATE `users` SET `points`='$new_points' WHERE id=" . $user_id;
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Points Added Successfully";
    $response['data'] = $res;

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));
?>
