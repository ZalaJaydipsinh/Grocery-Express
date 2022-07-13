<?php
   
   require('connection.php');

   $request= json_decode(file_get_contents('php://input'),true);

   if(isset($request['c_id']) && isset($request['i_id'])){
       
       $query = "delete from `i_category` where `c_id` = '".ltrim($request['c_id'],'c')."' and `i_id` = '".$request['i_id']."' limit 1";

        if(mysqli_query($conn,$query))
        {
            echo json_encode(true);
        }
        else{
            echo json_encode(mysqli_error($conn));
        }

    }

?>