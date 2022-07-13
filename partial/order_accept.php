<?php

    require('connection.php');

    $request= json_decode(file_get_contents('php://input'),true);

    if(isset($request['accept']) && $request['accept'] == 1 && isset($request['o_id']) && isset($request['d_id'])){
        
        $query = mysqli_query($conn,"UPDATE `orders` SET `o_status` = '1', `d_id` = '".$request['d_id']."' WHERE `orders`.`o_id` = ".$request['o_id']."");
        // UPDATE `deliveryboy` SET `d_active` = '0' WHERE `d_id` = 2

        if($query)
        {
            // echo (true);
            echo json_encode(true);
        }
        else{
            // echo (false);
            echo json_encode(false);
        }

 }
