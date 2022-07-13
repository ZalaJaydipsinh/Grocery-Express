<?php

    require('connection.php');

    $request= json_decode(file_get_contents('php://input'),true);

    if(isset($request['dboyId']) && isset($request['active'])){
        
        $query = mysqli_query($conn,"UPDATE `deliveryboy` SET `d_active` = '".$request['active']."' WHERE `d_id` = ".$request['dboyId']."");
        // UPDATE `deliveryboy` SET `d_active` = '0' WHERE `d_id` = 2
        $query2 = mysqli_query($conn,"update orders set `d_id` = null ,`o_status` = 0 where `d_id` = ".$request['dboyId']." and `o_status` <=2");
        if($query && $query2)
        {
            // echo (true);
            echo json_encode(true);
        }
        else{
            // echo (false);
            echo json_encode(false);
        }

 }
