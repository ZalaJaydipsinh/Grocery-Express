<?php

    require('connection.php');

    $request= json_decode(file_get_contents('php://input'),true);

    if(isset($request['item_Id']) && isset($request['active'])){
        
        $query = mysqli_query($conn,"UPDATE `items` SET `i_isselling` = '".$request['active']."' WHERE `i_id` = ".$request['item_Id']."");
        
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
