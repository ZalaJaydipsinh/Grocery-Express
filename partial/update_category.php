<?php

require('connection.php');
require('error_msg.php');

$id = $_POST['uc_id'];
$name = $_POST['uc_name'];

$query = "update category set `c_name` = '".$name."' where `c_id`= '".$id."'";
if(mysqli_query($conn,$query)){
    header("location: ../category.php?u=true&cat=".$name."");
}else{
    failure_msg("Category name is not updated.","../category.php","Category");
}

?>