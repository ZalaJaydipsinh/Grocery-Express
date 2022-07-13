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

$id = $_POST['i_id'];
$query = "SELECT `i_photo` FROM `items` WHERE `i_id` = " . $id . " limit 1";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);


if ($data['i_photo'] != $_POST['pic']) {
    //item img
    $ext = getExtension($_FILES['i_photo']['name']);
    $tmp_path = $_FILES['i_photo']['tmp_name'];
    $img_name =  "./pics/" . time() . rand(10, 99) . "." . $ext;

    if ($_FILES['i_photo']['size'] > (400000)) {
        echo "Image size should be <= 400Kb" . "<br>";
        $allok = false;
    }
    if (isset($ext) && ($ext == "jpeg" || $ext == "jpg" || $ext == "png")) {
        if (!move_uploaded_file($tmp_path, $img_name)) {
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
} else {
    $img_name = $data['i_photo'];
}

//item
$name = $_POST['i_name'];
$price = $_POST['i_price'];
$quantity = $_POST['i_quantity'];
$unit = $_POST['i_unit'];
$id;

if (isset($name) && isset($price) && isset($quantity) && isset($unit)) {
    begin();

    $query = "UPDATE `items` SET `i_name` = '" . $name . "', `i_price` = '" . $price . "', `i_quantity` = '" . $quantity . "', `i_unit` = '" . $unit . "', `i_photo` = '" . $img_name . "' WHERE `items`.`i_id` = " . $id . "";

    if (mysqli_query($conn, $query)) {
    } else {
        echo "Due to some serve problem item is not updated." . "<br>";
        echo mysqli_error($conn);
        rollback();
        //delete updated pic of item
        $path = realpath($img_name);
        if (is_writable($path)) {
            unlink($path);
        }
        exit();
    }
} else {
    echo "Some information(name,price,quantity,unit) about item is may be wrong.So, please try again." . "<br>";
    exit();
}


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

        $query = "SELECT * FROM `i_category` WHERE i_id = " . $id . " and c_id = " . $c_id . "";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) >= 1) {
            $query1 = "UPDATE `i_category` SET `c_id` = '" . $c_id . "' WHERE `i_category`.`i_id` = " . $id . " AND `i_category`.`c_id` = " . $c_id . ""; 
        } else {
            $query1 = "INSERT INTO `i_category` (`i_id`, `c_id`) VALUES (" . $id . ", " . $c_id . " )";
        }
        
        
        $result1 = mysqli_query($conn, $query1);
        if ($result1 != true && $firstIteration == true) {
            echo "Somethig is wrong while updating categories.So, please try again.";
            rollback();
            exit();
        }

        if (isset($sc_id) && isset($rev)) {
            $query2 = "INSERT INTO `c_dependency` (`s_id`, `c_id`, `reverse`) VALUES (" . $sc_id . ", " . $c_id . ", " . $rev . ")";
            $result2 = mysqli_query($conn, $query2);

            $query1 = "INSERT INTO `i_category` (`i_id`, `c_id`) VALUES (" . $id . ", " . $sc_id . " )";
            $result1 = mysqli_query($conn, $query1);

            if ($result2 != true && $firstIteration == true) {
                echo "Somethig is wrong while updating categories.So, please try again.";
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
header("location:../i_list_new.php?item=".$name."");
?>


<a href="../admin.php">Click here to jump to home page</a>