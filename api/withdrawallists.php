<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
$todaydate = date('Y-m-d');
$startdate = strtotime("-7 day", strtotime($todaydate));
$startdate = date('Y-m-d', $startdate);
$user_id = $db->escapeString($_POST['user_id']);
$sql = "SELECT * FROM withdrawal WHERE user_id = '$user_id' AND date BETWEEN '$startdate' AND '$todaydate' ORDER BY id DESC";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num > 0) {
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['points'] = $row['points'];
        if($row['status'] == 0){
            $temp['status'] = "Pending";
        }else if($row['status'] == 1){
            $temp['status'] = "Approved";
        }else if($row['status'] == 2){
            $temp['status'] = "Cancelled";
        }
        $temp['date_created'] = $row['date_created'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Withdrawal listed Successfully";
    $response['data'] = $rows;
}
else{
    $response['success'] = false;
    $response['message'] = "No data found";
}
    

    print_r(json_encode($response));
?>