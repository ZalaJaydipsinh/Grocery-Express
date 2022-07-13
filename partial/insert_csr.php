<?php

    require('connection.php');

    if(isset($_POST['category']) && isset($_POST['subcategory'])){
        $c_id = ltrim($_POST['category'], "c \t\n\r\0\x0B") ;
        $s_id = ltrim($_POST['subcategory'], "s \t\n\r\0\x0B") ;
        if(!isset($_POST['reverse'])){
            $rev = 0;
        }else{
            $rev = 1;
        }

        $query1 = "INSERT INTO `c_dependency` (`s_id`, `c_id`, `reverse`) VALUES (" . $s_id . ", " . $c_id . ", " . $rev . ")";
        if(mysqli_query($conn, $query1)){
            header("location: ../category.php");
        }else{
            echo "Relation is not inserted.";
            echo "<br>";
            echo "Error: ".mysqli_error($conn);
        }

    }
    else{
        echo "not set error. <pre>";
        print_r($_POST);
        echo "</pre>";

    }

?>