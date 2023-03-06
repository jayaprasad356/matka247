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
if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is Empty";
    print_r(json_encode($response));
    return false;
}
$date = $db->escapeString($_POST['date']);
$sql = "SELECT *,s.points AS points,s.date_created AS date_created FROM share s,users u WHERE s.user_id = u.id AND s.date_created like '%" . $date . "%' ORDER BY s.id DESC";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
    
if ($num >= 1) {
    foreach ($res as $row) {
        $shared_user_id=$row['shared_user_id'];
        $sql = "SELECT * FROM users WHERE id = '$shared_user_id'";
        $db->sql($sql);
        $resshare = $db->getResult();
        $shared_mobile = $resshare[0]['mobile'];
        $temp['id'] = $row['id'];
        $temp['mobile'] = $row['mobile'];
        $temp['points'] = $row['points'];
        $temp['date'] = $row['date'];
        $temp['date_created'] = $row['date_created'];
        $temp['shared_mobile'] = $shared_mobile;
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