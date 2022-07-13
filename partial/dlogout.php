<?php

    session_start();
    session_unset();
    session_destroy();
    if (isset($_COOKIE['dboyname']) && isset($_COOKIE['dpassword']))
    {   
 
        unset($_COOKIE['dboyname']);
        unset($_COOKIE['dpassword']);      
        setcookie("dboyname",'',1,'/');
        setcookie("dpassword",'',1,'/');
    }
    // echo "<pre>";
    // print_r($_COOKIE);
    // echo "</pre>";
    header("location: ../dboy.php?logout=true");
    exit();

?>