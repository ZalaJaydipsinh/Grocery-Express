<?php

$host = "localhost";
$user = "root";
$passwd = "root";
$db = "project";

@$conn = mysqli_connect($host,$user,$passwd,$db);

if(!$conn){
    die('Connection Error: '.mysqli_connect_error());
}

?>