<?php
    require('connection.php');

    $request= json_decode(file_get_contents('php://input'),true);

    if (isset($request['name'])) {

        $cat_name = ucwords(strtolower($request['name']));

        $query = "INSERT INTO `category` (`c_name`) VALUES ('".$cat_name."')";


        if (mysqli_query($conn,$query)) {
            echo json_encode(true);
        } else {
            echo json_encode(mysqli_error($conn));
        }
    }
?>

