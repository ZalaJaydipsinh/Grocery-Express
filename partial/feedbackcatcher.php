<?php
session_start();
require('connection.php');


@$message = htmlentities(trim($_POST['msg']));
@$uid = $_SESSION['userid'];


if (isset($message)) {
    $query = "INSERT INTO `feedback`(`u_id`,`feedback`) VALUES ('$uid', '$message')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header('location:../contactus.php?msg=true');
    } else {
        header('location:../contactus.php?msg=false');
        // echo "error: ".mysqli_error($conn);
        // exit();
    }
}
?>

    
    
