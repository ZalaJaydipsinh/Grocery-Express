<?php

require('./partial/connection.php');

session_start();

if (!isset($_SESSION['dboyid'])) {
    header("location: dboy.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Of Order</title>
    <?php
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>
    <style>
        hr {
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr {
            border-bottom: 1px solid grey;
        }

        thead>tr {
            border-bottom: 2px solid grey;
        }

        th,
        td {
            text-align: left;
        }

        .container {
            width: 80%;
        }
    </style>
</head>

<body>
    <?php
    require('./partial/dnav.php');
    ?>
    <div class="container">
        <?php
        $query = "SELECT * FROM `orders` where d_id = " . $_SESSION['dboyid'] . " and o_status = 3";
        $result = mysqli_query($conn, $query);
        $total_orders = mysqli_num_rows($result);

        echo '<button class="btn btn-primary m-3">
                    Total orders:  <span class="badge bg-danger">'.$total_orders.'</span>
                    <span class="sr-only">unread messages</span>
            </button>';


        if (mysqli_num_rows($result) == 0) {
            echo '<h6 class="display-4 text-warning mt-5 mx-auto">No any orders are in the history.</h6>';
        }

        while ($order = mysqli_fetch_assoc($result)) {

        ?>
            <div class="card m-2 shadow p-1" id="bill">
                <div class="w-100 mt-1">
                    <h6 class="float-left d-inline-flex">Order Id.: <?php echo $order['o_id']; ?></h6>
                    <h6 class="float-right d-inline-flex mr-2">Date: &nbsp;&nbsp;
                        <?php
                        echo date("d/m/Y - h:i:s - A", strtotime($order['o_delivery_time']));
                        ?>
                    </h6>
                </div>
                <hr>
                <table>
                    <thead border="1">
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Units</th>
                            <th>Price(of 1)</th>
                            <th>Sub-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query2 = "SELECT i_name,i_quantity,oi_quantity,i_price,i_price*oi_quantity as price FROM `order_items` join items on order_items.i_id = items.i_id  where o_id = " . $order['o_id'] . "";
                        $result2 = mysqli_query($conn, $query2);
                        $total = 0;
                        while ($order_item = mysqli_fetch_assoc($result2)) {
                            echo "<tr>";
                            echo "<td>" . $order_item['i_name'] . "</td>";
                            echo "<td>" . $order_item['i_quantity'] . "</td>";
                            echo "<td>" . $order_item['oi_quantity'] . "</td>";
                            echo "<td>" . $order_item['i_price'] . "</td>";
                            echo "<td>" . $order_item['price'] . "</td>";
                            echo "</tr>";
                            $total += $order_item['price'];
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th><?php echo $total; ?></th>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td colspan="4"><?php echo $order['o_address']; ?></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        <?php
        }
        ?>
    </div>

</body>
<?php
require("./partial/script.php");
?>

</html>