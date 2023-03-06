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
if (empty($_POST['id'])) {
    $response['success'] = false;
    $response['message'] = "Id is Empty";
    print_r(json_encode($response));
    return false;
}
$id = $db->escapeString($_POST['id']);
$sql = "SELECT * FROM results WHERE id = '$id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
$date = $res[0]['date'];
$game_name = $res[0]['game_name'];
$result = $res[0]['result'];
if ($num == 1) {
    $sql = "SELECT * FROM games WHERE game_date = '$date' AND game_name = '$game_name' AND number = '$result'";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row) {
        $user_id = $row['user_id'];
        $game_type = $row['game_type'];
        $sql = "SELECT * FROM winners WHERE user_id = '$user_id' AND game_name = '$game_name' AND date = '$date' AND result = '$result' AND game_type = 'games'";
        $db->sql($sql);
        $reswin = $db->getResult();
        $winpoints = $reswin[0]['points'];
        $sql = "DELETE FROM winners WHERE user_id = '$user_id' AND game_name = '$game_name' AND date = '$date' AND result = '$result'  AND game_type = 'games'";
        $db->sql($sql);
        $sql = "UPDATE users SET points = points - '$winpoints' WHERE id = '$row[user_id]'";
        $db->sql($sql);
        $sql = "SELECT * FROM users WHERE id = '$row[user_id]'";
        $db->sql($sql);
        $res = $db->getResult();
        $update_user_points = $res[0]['points'];
        $datetime = Date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,game_type,date,date_created) VALUES('$user_id','$winpoints','$update_user_points','wrongresult','$game_name','$game_type','$date','$datetime')" ;
        $db->sql($sql);
    }
    $harufresult = sprintf("%02d", $result);
    $andarresult = substr($harufresult, 0, 1); 
    $baharresult = substr($harufresult, 1, 2); 
    $sql = "SELECT * FROM haruf WHERE game_date = '$date' AND game_name = '$game_name' AND number = '$andarresult' AND game_type = 'andar'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['haruf_andar'] = $res;

    foreach ($res as $row) {
        $user_id = $row['user_id'];
        $sql = "SELECT * FROM winners WHERE user_id = '$user_id' AND game_name = '$game_name' AND date = '$date' AND result = '$result'  AND game_type = 'andar'";
        $db->sql($sql);
        $reswin = $db->getResult();
        $winpoints = $reswin[0]['points'];
        $sql = "DELETE FROM winners WHERE user_id = '$user_id' AND game_name = '$game_name' AND date = '$date' AND result = '$result'  AND game_type = 'andar'";
        $db->sql($sql);
        $sql = "UPDATE users SET points = points - '$winpoints' WHERE id = '$row[user_id]'";
        $db->sql($sql);
        $sql = "SELECT * FROM users WHERE id = '$row[user_id]'";
        $db->sql($sql);
        $res = $db->getResult();
        $update_user_points = $res[0]['points'];
        $datetime = Date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,game_type,date,date_created) VALUES('$user_id','$winpoints','$update_user_points','wrongresult','$game_name','andar','$date','$datetime')" ;
        $db->sql($sql);
    }
    $sql = "SELECT * FROM haruf WHERE game_date = '$date' AND game_name = '$game_name' AND number = '$baharresult' AND game_type = 'bahar'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['haruf_bahar'] = $res;

    foreach ($res as $row) {
        $user_id = $row['user_id'];
        $sql = "SELECT * FROM winners WHERE user_id = '$user_id' AND game_name = '$game_name' AND date = '$date' AND result = '$result'  AND game_type = 'bahar'";
        $db->sql($sql);
        $reswin = $db->getResult();
        $winpoints = $reswin[0]['points'];
        $sql = "DELETE FROM winners WHERE user_id = '$user_id' AND game_name = '$game_name' AND date = '$date' AND result = '$result'  AND game_type = 'bahar'";
        $db->sql($sql);
        $sql = "UPDATE users SET points = points - '$winpoints' WHERE id = '$row[user_id]'";
        $db->sql($sql);
        $sql = "SELECT * FROM users WHERE id = '$row[user_id]'";
        $db->sql($sql);
        $res = $db->getResult();
        $update_user_points = $res[0]['points'];
        $datetime = Date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $sql = "INSERT INTO `transactions` (user_id,points,balance,type,game_name,game_type,date,date_created) VALUES('$user_id','$winpoints','$update_user_points','wrongresult','$game_name','bahar','$date','$datetime')" ;
        $db->sql($sql);
    }
    $sql = "DELETE FROM results WHERE id = '$id'";
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "Delete Result Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Results Found";
    print_r(json_encode($response));
}

?>