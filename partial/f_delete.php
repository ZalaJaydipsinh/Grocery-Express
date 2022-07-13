<?php

require('./connection.php');

$query = "delete from feedback WHERE f_id =  " . $_REQUEST['delete'] . "";
$result = mysqli_query($conn, $query);

if ($result == true) {
    header("location: ../feedback_list.php?d=true");
} else {
    echo "feedback is not delete.So please try again.";
    echo "<br>";
    echo "Error: ".mysqli_error($conn);
}
