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
if (empty($_POST['DS'])) {
    $response['success'] = false;
    $response['message'] = "DS is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['GB'])) {
    $response['success'] = false;
    $response['message'] = "GB is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['GL'])) {
    $response['success'] = false;
    $response['message'] = "GL is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['FD'])) {
    $response['success'] = false;
    $response['message'] = "FD is Empty";
    print_r(json_encode($response));
    return false;
}
$date = $db->escapeString($_POST['date']);
$day= $db->escapeString($_POST['day']);
$month= $db->escapeString($_POST['month']);
$year= $db->escapeString($_POST['year']);
$DS= $db->escapeString($_POST['DS']);
$GB= $db->escapeString($_POST['GB']);
$GL= $db->escapeString($_POST['GL']);
$FD= $db->escapeString($_POST['FD']);
$sql = "SELECT * FROM results WHERE date = '$date'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 0) {
    $sql= "INSERT INTO results (date,day,month,year,DS,GB,GL,FD) VALUES ('$date','$day','$month','$year','$DS','$GB','$GL','$FD')";
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "Result Posted Successfully";

}
else{
    $response['success'] = false;
    $response['message'] = "Results Already Exist";
}

print_r(json_encode($response));
?>
