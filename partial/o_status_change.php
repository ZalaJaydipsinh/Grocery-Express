<?php

// delivered
// packed

session_start();
require('connection.php');

if(isset($_POST['packed'])){
    $query = "UPDATE `orders` SET `o_status` = '2' WHERE `orders`.`o_id` = ".$_POST['packed']."";
}elseif(isset($_POST['delivered'])){
    $query = "UPDATE `orders` SET `o_status` = '3',`o_delivery_time` = now() WHERE `orders`.`o_id` = ".$_POST['delivered']."";
    $query2 = "update deliveryboy set d_orders = d_orders + 1 where d_id = ".$_SESSION['dboyid']." ";
    mysqli_query($conn,$query2);
}

if(isset($query)){
    if(mysqli_query($conn,$query)){
        header("location: ../my_deliveries.php");
        exit();
    }
}
echo "Error: ".mysqli_error($conn);
?>