<?php

    session_start();
    session_unset();
    session_destroy();
    if (isset($_COOKIE['apassword']) && isset($_COOKIE['apassword']))
    {   
 
        unset($_COOKIE['adminname']);
        unset($_COOKIE['apassword']);      
        setcookie("adminname",'',1,'/');
        setcookie("apassword",'',1,'/');
    }
    // echo "<pre>";
    // print_r($_COOKIE);
    // echo "</pre>";
    header("location: ../admin.php?logout=true");
    exit();

?>