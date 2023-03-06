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
if (empty($_POST['search'])) {
    $response['success'] = false;
    $response['message'] = "Search Term is Empty";
    print_r(json_encode($response));
    return false;
}
$search = $db->escapeString($_POST['search']);
$sql = "SELECT * FROM users WHERE name like '%" . $search . "%' OR mobile like '%" . $search . "%' ";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Users listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No User Found";
    $response['data'] = $res;
    print_r(json_encode($response));

}
?>