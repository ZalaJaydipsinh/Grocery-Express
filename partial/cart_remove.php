<?php
require('connection.php');

$request = json_decode(file_get_contents('php://input'), true);

$id = $request['i_id'];
$count = $request['count'];
$op = $request['op'];
$u_id = $request['u_id'];
if (isset($id) && isset($count) && isset($op) && isset($u_id) && $u_id != -1) {
    // if (false) {
        $query = "SELECT `COUNT` FROM `cart` where i_id = ".$id." and u_id = ".$u_id."";
        $result = mysqli_query($conn,$query);
        $data = mysqli_fetch_array($result);

    if ($op == "delete") {
        $query = "DELETE FROM `cart` WHERE `i_id` = " . $id . " AND `u_id` = " . $u_id . "";
        echo (mysqli_query($conn, $query) ?  json_encode((!isset($data[0])) ? 0 : $data[0]) : json_encode(false));
        // echo (mysqli_query($conn,$query)?json_encode(true):json_encode(false));
    }else{
        echo json_encode(false);
    }
} else {
    echo json_encode(false);
    // echo json_encode("not set");
}

?>