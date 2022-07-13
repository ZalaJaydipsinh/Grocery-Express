<?php
   
   require('connection.php');

   $request= json_decode(file_get_contents('php://input'),true);

   if(isset($request['name'])){
       
       $query = mysqli_query($conn,"select `d_name` from `deliveryboy` where `d_name` = '".$request['name']."' limit 1");
        

        if(mysqli_num_rows($query) >= 1)
        {
            // echo (true);
            echo json_encode(true);
        }
        else{
            // echo (false);
            echo json_encode(false);
        }

    }

?>
