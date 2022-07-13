<?php

    require('connection.php');

    $request= json_decode(file_get_contents('php://input'),true);

    if(isset($request['itemId']) && isset($request['unit'])){
        $sql ="update items set i_unit = ".$request['unit']." WHERE i_id = ".$request['itemId']."";
        $query = mysqli_query($conn,$sql);
        if($query)
        {
            // echo json_encode(true);
            $arr[] = $request['itemId'];
            $arr[] = $request['unit'];
            echo json_encode($arr);
        }
        else{
            echo json_encode(false);
        }

 }
