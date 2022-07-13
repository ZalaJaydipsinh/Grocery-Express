<?php

require("connection.php");

// $query = "select * from category";
$query = "select * from category order by rand()";
$result = mysqli_query($conn, $query);

while ($data = mysqli_fetch_assoc($result)) {
    $query1 = "select * from items where i_id in (select i_id from i_category where c_id = " . $data['c_id'] . ") and i_isselling = true";
    $result1 = mysqli_query($conn, $query1);
    if (mysqli_num_rows($result1) > 0) {

        echo "<div class='main'>
    <div class='categoryname container-fluid positon-relative clearfix'>
        <h3 class='main-heading'>" . $data['c_name'] . "</h3>
        <a href='./view_cat.php?c_id=" . $data['c_id'] . "'><h2 class='btn btn-outline-danger float-right'>View all</h2></a>
    </div>
    <div class='category' id = '" . $data['c_name'] . "'>";

        while ($data = mysqli_fetch_assoc($result1)) {
            echo '<div class="card shadow-sm m-1" style="width: 150px; height: 300px;">
            <img src="' . $data['i_photo'] . '" class="card-img-top" alt="demo image" height="150px">
            <div class="card-body">
                <div class="detail">
                <h6 class="card-subtitle"> &#8377;' . $data['i_price'] . ' </h6>
                <p class="mt-1" style="font-size:12px;">
                    <span>' . $data['i_name'] . '</span> <br>
                    <span>' . $data['i_quantity'] . '</span>
                </p>
                </div>
                <button onclick="cart_btn(this,' . $data['i_id'] . ')" class="btn btn-outline-secondary cart">Add to cart</button>
                <div class="number-input d-none">
                    <button onclick="count_down(this,' . $data['i_id'] . ')"></button>
                    <input class="quantity" readonly min="0" name="quantity" value="1" type="number">
                    <button onclick="count_up(this,' . $data['i_id'] . ')" class="plus"></button>
                </div>
            </div>
        </div>
        ';
        }

        echo '</div><button class="btn btn-light left"  onclick="sideScroll(this.parentNode.querySelector(\'.category\'),\'left\',25,150 * 2,10)">&lt;</button>
    <button class="btn btn-light right"  onclick="sideScroll(this.parentNode.querySelector(\'.category\'),\'right\',25,150 * 2,10)">&gt;</button>
    </div>';
    }
}

?>