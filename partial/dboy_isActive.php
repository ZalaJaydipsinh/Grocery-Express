<?php

require('./partial/connection.php');

session_start();

if (!isset($_SESSION['dboyid'])) {
    header("location: dboy.php");
    exit();
}

$query = "select `d_active` from deliveryboy where `d_id` = ".$_SESSION['dboyid']." limit 1";
$result = mysqli_query($conn,$query);
$data = mysqli_fetch_assoc($result);

if(isset($data) && $data['d_active'] == 1 ){
    $_SESSION['dboy_isActive'] = 1;
}else{
    $_SESSION['dboy_isActive'] = 0;
    header("location: dboy.php");
    exit();
}

?>