<?php

require('./connection.php');
require('error_msg.php');

if (isset($_REQUEST['cat']) && isset($_REQUEST['sub'])) {
    $query = "delete from c_dependency where c_id = " . $_REQUEST['cat'] . " and s_id = " . $_REQUEST['sub'] . "";
    if (mysqli_query($conn, $query)) {
        header("location: ../category.php?d=true&cat=".$_REQUEST['cat']."&sub=".$_REQUEST['sub']."");
    } else {
        failure_msg("category subcategory relation is not delete.So please try again.","../category.php","Category");
    }
}else if(isset($_REQUEST['delete'])){
    $query = "delete from category where c_id = " . $_REQUEST['delete'] ."";
    if (mysqli_query($conn, $query)) {
        header("location: ../category.php?d=true&cat=".$_REQUEST['delete']."");
    } else {
        failure_msg("category is not delete.So please try again.","../category.php","Category");
    }
}
?>