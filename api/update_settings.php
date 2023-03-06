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
$whatspp_number = (isset($_POST['whatspp_number']) && $_POST['whatspp_number'] != "") ? $db->escapeString($_POST['whatspp_number']) : "";
$youtube_link = (isset($_POST['youtube_link']) && $_POST['youtube_link'] != "") ? $db->escapeString($_POST['youtube_link']) : "";
$upi = (isset($_POST['upi']) && $_POST['upi'] != "") ? $db->escapeString($_POST['upi']) : "";
$newsinfo = (isset($_POST['newsinfo']) && $_POST['newsinfo'] != "") ? $db->escapeString($_POST['newsinfo']) : "";
$min_withdrawal = (isset($_POST['min_withdrawal']) && $_POST['min_withdrawal'] != "") ? $db->escapeString($_POST['min_withdrawal']) : "";
$max_withdrawal = (isset($_POST['max_withdrawal']) && $_POST['max_withdrawal'] != "") ? $db->escapeString($_POST['max_withdrawal']) : "";
$min_deposit = (isset($_POST['min_deposit']) && $_POST['min_deposit'] != "") ? $db->escapeString($_POST['min_deposit']) : "";
$sql = "UPDATE settings SET whatspp_number = '$whatspp_number', youtube_link = '$youtube_link', upi = '$upi', newsinfo = '$newsinfo', newsinfo = '$newsinfo', newsinfo = '$newsinfo', min_withdrawal = $min_withdrawal, max_withdrawal = $max_withdrawal, min_deposit = $min_deposit WHERE id = 1";
$db->sql($sql);
$sql = "SELECT * FROM settings";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Settings Updated Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Bids Found";
    $response['data'] = $res;
    print_r(json_encode($response));

}
?>