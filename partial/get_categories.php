<?php
require('connection.php');

$request = json_decode(file_get_contents('php://input'), true);

if (isset($request['name'])) {

    $query = "SELECT * FROM `category`";

    $result = mysqli_query($conn,$query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

?>