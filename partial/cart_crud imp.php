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

    if ($op == "insert" && !isset($data[0])) {
        $query = "INSERT INTO `cart` (`i_id`, `count`, `u_id`) VALUES ('".$id."', '".$count."', '".$u_id."')";
    } else if ($op == "delete" && isset($data[0]) && $data[0] == 1) {
        $query = "DELETE FROM `cart` WHERE `i_id` = ".$id." AND `u_id` = ".$u_id."";
    } else if (isset($data[0]) && (($op == "down" && ($data[0]-1) == $count) xor ($op == "up" && ($data[0]+1) == $count))) {
        $query = "UPDATE `cart` SET  `count` = '".$count."' WHERE `i_id` = ".$id." AND `u_id` = ".$u_id."";
    } else {
        // echo json_encode("op... wrong: ".$op." db: ".$data[0]." this: ".$count);
        echo json_encode((!isset($data[0]))?0:$data[0]);
        // echo json_encode(false);
        exit();
    }
    // echo (mysqli_query($conn,$query)?json_encode("query true"):json_encode("query false"));
    echo (mysqli_query($conn,$query)?json_encode(true):json_encode((!isset($data[0]))?0:$data[0]));
} else {
    echo json_encode(false);
    // echo json_encode("not set");
}
?>