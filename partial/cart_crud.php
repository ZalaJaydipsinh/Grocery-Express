<?php
require('connection.php');

function is_avail_item($id, $count)
{
    global $conn;
    $query9 = "SELECT `i_unit`,`i_name`,`i_quantity` FROM `items` where `i_id` = " . $id . "";
    $result9 = mysqli_query($conn, $query9);
    $record = mysqli_fetch_assoc($result9);
    $i_units = $record['i_unit'];

    if ($count > $i_units) {
        return "We have only " . $i_units . " units of " . $record['i_quantity'] . " , " . $record['i_name'];
    }
    return "avail";
}

$request = json_decode(file_get_contents('php://input'), true);

$id = $request['i_id'];
$count = $request['count'];
$op = $request['op'];
$u_id = $request['u_id'];
if (isset($id) && isset($count) && isset($op) && isset($u_id) && $u_id != -1) {
    // if (false) {

    $arr = [];
    $query = "SELECT `COUNT` FROM `cart` where i_id = " . $id . " and u_id = " . $u_id . "";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_array($result);

    if ($op == "insert" && !isset($data[0])) {
        $query = "INSERT INTO `cart` (`i_id`, `count`, `u_id`) VALUES ('" . $id . "', '" . $count . "', '" . $u_id . "')";
    } else if ($op == "delete" && isset($data[0]) && $data[0] == 1) {
        $query = "DELETE FROM `cart` WHERE `i_id` = " . $id . " AND `u_id` = " . $u_id . "";
    } else if (isset($data[0]) && (($op == "down" && ($data[0] - 1) == $count) xor ($op == "up" && ($data[0] + 1) == $count))) {
        $query = "UPDATE `cart` SET  `count` = '" . $count . "' WHERE `i_id` = " . $id . " AND `u_id` = " . $u_id . "";
    } else {
        // echo json_encode("op... wrong: ".$op." db: ".$data[0]." this: ".$count);
        $arr[] = "";
        $arr[1] = (!isset($data[0])) ? 0 : $data[0];
        $arr[0] = is_avail_item($id, $arr[1]);
        echo json_encode($arr);
        // echo json_encode(false);
        exit();
    }
    // echo (mysqli_query($conn,$query)?json_encode("query true"):json_encode("query false"));
    $arr[0] = is_avail_item($id, $count);
    if($arr[0] == "avail"){
        $arr[1] = mysqli_query($conn, $query) ? true : ((!isset($data[0])) ? 0 : $data[0]);
    }else{
        $arr[1] = "not avail";
    }
    // $arr[0] = is_avail_item($id, $arr[1]);
    echo json_encode($arr);
    // echo (mysqli_query($conn, $query) ? json_encode(true) : json_encode((!isset($data[0])) ? 0 : $data[0]));
} else {
    $arr = array("Please log in", false);
    echo json_encode($arr);
    // echo json_encode("not set");
}
