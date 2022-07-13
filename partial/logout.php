<?php

    session_start();
    session_unset();
    session_destroy();
    if (isset($_COOKIE['username']) && isset($_COOKIE['password']))
    {   
 
        unset($_COOKIE['username']);
        unset($_COOKIE['password']);      
        setcookie("username",'',1,'/');
        setcookie("password",'',1,'/');
    }
    // echo "<pre>";
    // print_r($_COOKIE);
    // echo "</pre>";
    header("location: ../index.php?logout=true");
    exit();

?>