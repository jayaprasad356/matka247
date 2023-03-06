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
$month = $db->escapeString($_POST['month']);
$year = $db->escapeString($_POST['year']);
$sql = "SELECT date, SUM(CASE WHEN game_name='GB' THEN result  END) as GB,  SUM(CASE WHEN game_name='GL' THEN result END) as GL, SUM(CASE WHEN game_name='DS' THEN result END) as DS,  SUM(CASE WHEN game_name='FD' THEN result END) as FD FROM `results` WHERE month='$month' AND year='$year' GROUP BY date ORDER BY date ASC";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    foreach ($res as $row) {
        $temp['GB'] = $row['GB'];
        $temp['GL'] = $row['GL'];
        $temp['DS'] = $row['DS'];
        $temp['FD'] = $row['FD'];
        $temp['date'] = $row['date'];
        $rows[] = $temp;
    }
    
    $response['success'] = true;
    $response['message'] = "Results listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Results Found";
    print_r(json_encode($response));
}
?>