<?php

require('connection.php');

$request = json_decode(file_get_contents('php://input'), true);

if (isset($request['cs_id']) && isset($request['reverse'])) {

    $cs_id = $request['cs_id'];
    $pos = strpos($cs_id, "|");
    $c_id =  substr($cs_id, 0, $pos);
    $s_id = substr($cs_id, $pos + 1);

    $query = mysqli_query($conn, "UPDATE `c_dependency` SET `reverse` = '" . $request['reverse'] . "' WHERE `c_id` = " . $c_id . " and `s_id` = " . $s_id . "");
    if ($query) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}
