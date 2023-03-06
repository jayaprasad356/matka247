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

if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
    print_r(json_encode($response));
    return false;
}

$mobile= $db->escapeString($_POST['mobile']);
$device_id = $db->escapeString($_POST['device_id']);
$sql = "SELECT * FROM users WHERE mobile ='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num == 1){
    if($res[0]['user_status'] == 0){
        $response['success'] = false;
        $response['message'] = "Your account is not activated";
        $response['data'] = NULL;
        print_r(json_encode($response));
        return false;
    }
    elseif($res[0]['user_status'] == 2){
        $response['success'] = false;
        $response['message'] = "Your account is Blocked Contact Admin";
        $response['data'] = NULL;
        print_r(json_encode($response));
        return false;
    }
    else{
        $sql = "UPDATE users SET loggedin = 1, device_id = '$device_id' WHERE mobile = '$mobile'";
        $db->sql($sql);
    
        $response['success'] = true;
        $response['login'] = true;
        $response['message'] = "Logged In Successfully";
        $response['data'] = $res;

    }


}
else{
    $response['success'] = true;
    $response['login'] = false;
    $response['message'] = "Logged In Successfully";
    $response['data'] = $res;

}
print_r(json_encode($response));

?>