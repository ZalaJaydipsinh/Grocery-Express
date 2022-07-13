<?php

require('./connection.php');

$query1 = "delete from items where i_id = " . $_REQUEST['delete'] . "";
$query2 = "select i_photo from items where i_id =" . $_REQUEST['delete'] . "";
$result = mysqli_query($conn, $query2);

if ($result == true) {
    $data = mysqli_fetch_assoc($result);
    $path = realpath("." . $data['i_photo']);
    if (is_writable($path)) {
        unlink($path);
    }
    if (mysqli_query($conn, $query1)) {
        header("location: ../i_list_new.php?d=true");
    }else{
    echo "item is not delete.So please try again.";
    echo "<br>";
    echo "Error: ".mysqli_error($conn);
    }
} else {
    echo "item is not delete.So please try again.";//img is not fetched
    echo "<br>";
    echo "Error: ".mysqli_error($conn);
}
