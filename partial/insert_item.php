<?php

require('connection.php');

//imp functions
function getExtension($str)
{
    return substr($str, strrpos($str, ".") + 1);
}

function begin()
{
    global $conn;
    mysqli_query($conn, "BEGIN");
}

function commit()
{
    global $conn;
    mysqli_query($conn, "COMMIT");
}

function rollback()
{
    global $conn;
    mysqli_query($conn, "ROLLBACK");
}


$allok = true;

//item img
$ext = getExtension($_FILES['i_photo']['name']);
$tmp_path = $_FILES['i_photo']['tmp_name'];
$img_name =  "./pics/" . time() . rand(10, 99) . "." . $ext;

if ($_FILES['i_photo']['size'] > (400000)) {
    echo "Image size should be <= 400Kb" . "<br>";
    $allok = false;
}
if (isset($ext) && ($ext == "jpeg" || $ext == "jpg" || $ext == "png")) {
    if (!move_uploaded_file($tmp_path, ".".$img_name)) {
        echo "Image is not uploaded due to some reasons. So, please try again." . "<br>";
        $allok = false;
    }
} else {
    echo "Image format is not compatible. it should be jpg/jpeg/png." . "<br>";
    $allok = false;
}

if (!$allok) {
    exit();
}

//item
$name = $_POST['i_name'];
$price = $_POST['i_price'];
$quantity = $_POST['i_quantity'];
$unit = $_POST['i_unit'];
$i_id;

if (isset($name) && isset($price) && isset($quantity) && isset($unit)) {
    begin();
    $query = "INSERT INTO `items` (`i_id`, `i_name`, `i_price`, `i_quantity`, `i_photo`, `i_unit`) VALUES (NULL, '" . $name . "', '" . $price . "', '" . $quantity . "', '" . $img_name . "' , " . $unit . " )";
    if (mysqli_query($conn, $query)) {
        $i_id = mysqli_insert_id($conn);
    } else {
        echo "Due to some serve problem item is not inserted." . "<br>";
        echo mysqli_error($conn);
        rollback();
        //delete inserted pic of item
        $path = realpath(".".$img_name);
        if (is_writable($path)) {
            unlink($path);
        }
        exit();
    }
} else {
    echo "Some information(name,price,quantity,unit) about item is may be wrong.So, please try again." . "<br>";
    exit();
}

//cat - sub-cat relation
$firstIteration = true;
foreach ($_POST as $key => $value) {
    if (!is_array($value)) {
        continue;
    } else {
        $count = count($value);

        for ($j = 0; $j < $count; $j++) {
            if ($j == 0) {
                $c_id = ltrim($value[$j], "c \t\n\r\0\x0B");
            } else if ($j == 1) {
                $sc_id = ltrim($value[$j], "s \t\n\r\0\x0B");
            } else {
                $rev =  ($value[$j] == 1) ? 1 : 0;
            }
        }

        $query1 = "INSERT INTO `i_category` (`i_id`, `c_id`) VALUES (" . $i_id . ", " . $c_id . " )";
        $result1 = mysqli_query($conn, $query1);
        if ($result1 != true && $firstIteration == true) {
            echo "Somethig is wrong while inserting categories.So, please try again.";
            rollback();
            exit();
        }
        if (isset($sc_id) && isset($rev)) {
            $query2 = "INSERT INTO `c_dependency` (`s_id`, `c_id`, `reverse`) VALUES (" . $sc_id . ", " . $c_id . ", " . $rev . ")";
            $result2 = mysqli_query($conn, $query2);

            $query1 = "INSERT INTO `i_category` (`i_id`, `c_id`) VALUES (" . $i_id . ", " . $sc_id . " )";
            $result1 = mysqli_query($conn, $query1);
            
            if ($result2 != true && $firstIteration == true) {
                echo "Somethig is wrong while inserting categories.So, please try again.";
                rollback();
                exit();
            }
            unset($sc_id);
            unset($rev);
        }
    }
    $firstIteration = false;
}
commit();
header("location:../i_insert.php?item=".$name."");
?>


<a href="../admin.php">Click here to jump to home page</a>