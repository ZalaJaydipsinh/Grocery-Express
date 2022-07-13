<?php
require('./partial/connection.php');

session_start();

if (!isset($_SESSION['userid'])) {
    header("location: index.php");
    exit();
}


?>
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <symbol id="icon-bin" viewBox="0 0 32 32">
            <path d="M4 10v20c0 1.1 0.9 2 2 2h18c1.1 0 2-0.9 2-2v-20h-22zM10 28h-2v-14h2v14zM14 28h-2v-14h2v14zM18 28h-2v-14h2v14zM22 28h-2v-14h2v14z"></path>
            <path d="M26.5 4h-6.5v-2.5c0-0.825-0.675-1.5-1.5-1.5h-7c-0.825 0-1.5 0.675-1.5 1.5v2.5h-6.5c-0.825 0-1.5 0.675-1.5 1.5v2.5h26v-2.5c0-0.825-0.675-1.5-1.5-1.5zM18 4h-6v-1.975h6v1.975z"></path>
        </symbol>
    </defs>
</svg>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <?php
    // require('./testing/new style.php');
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>
    <style>
        /* button  */
        input[type="number"] {
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .number-input {
            border: 1px solid grey;
            display: inline-flex;
        }

        .number-input,
        .number-input * {
            box-sizing: border-box;
        }

        .number-input button {
            outline: none;
            -webkit-appearance: none;
            background-color: transparent;
            border: none;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            cursor: pointer;
            margin: 0;
            position: relative;
        }

        .number-input button:before,
        .number-input button:after {
            display: inline-block;
            position: absolute;
            content: '';
            width: .5rem;
            height: 2px;
            background-color: #212121;
            transform: translate(-50%, -50%);
        }

        .number-input button.plus:after {
            transform: translate(-50%, -50%) rotate(90deg);
        }

        .number-input input[type=number] {
            font-family: sans-serif;
            max-width: 3rem;
            padding: .5rem;
            border: 1px solid grey;
            border-width: 0 2px;
            font-size: 1rem;
            height: 2rem;
            font-weight: bold;
            text-align: center;
        }

        .card {
            width: 300px;
            height: 180px;
            overflow: hidden;
            float: left;
        }

        .bin {
            position: absolute;
            right: 10px;
            top: 5px;
            cursor: pointer;
        }

        .detail {
            min-height: 100px;
        }
    </style>
</head>

<body>
    <?php
    require("./partial/nav.php");
    ?>


    <!-- Modal -->
    <div class="modal fade" id="orederAddress" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="orederAddressLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="./order.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orederAddressLabel">Order and Address confirmation</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">
                        Your default delivery address is as below. <br>
                        You can also change it for this order.
                    </div>
                    <label for="InputEmail" class="form-label font-weight-bold">Address</label>
                    <textarea name="address" onkeyup="addressValidation()" rows="6" minlength="30" maxlength="1500" id="address" class="form-control" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" required><?php
                                                                                                                                                                                                                                                                        $query = "select u_address from user where u_id = " . $_SESSION['userid'] . "";
                                                                                                                                                                                                                                                                        $result = mysqli_query($conn, $query);
                                                                                                                                                                                                                                                                        $data =  mysqli_fetch_assoc($result);
                                                                                                                                                                                                                                                                        echo $data['u_address'];
                                                                                                                                                                                                                                                                        $default_addrs = $data['u_address'];
                                                                                                                                                                                                                                                                        ?>
                    </textarea>

                    <button type="reset" class="btn btn-outline-dark mt-2">Reset Address</button>
                    <button type="button" class="btn btn-outline-dark mt-2" onclick="clear_addrs()">Clear Address</button>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancle</button>
                    <button type="submit" class="btn btn-danger">Order Now</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <nav class="navbar navbar-light container" style="background-color: #e3f2fd;">
        <div class="row">
            <div class="col-4">Total Unique Items: <span id="unique_items">0</span></div>
            <div class="col-4 text-center">Total Amount: &#8377;<span id="total_amount">0</span></div>
            <div class="col-4"> <button class="btn btn-outline-danger float-right" data-toggle="modal" data-target="#orederAddress">Order Now</button></div>
        </div>
    </nav>
    <div class="container mt-2">
        <?php
        $query1 = "select cart.i_id,i_name,i_price,i_quantity,i_unit,i_photo,cart.count from items join cart on cart.i_id=items.i_id WHERE u_id = " . $_SESSION['userid'] . "";
        // echo $query1;
        // exit();
        $result1 = mysqli_query($conn, $query1);
        $unique_items = mysqli_num_rows($result1);
        $total_amount = 0;
        while ($data = mysqli_fetch_assoc($result1)) {
            $amount = $data['i_price'] * $data['count'];
            $total_amount += $amount;
            echo '<div class="card shadow-sm m-1 ml-5">
        <div class="row">
            <div class="col-6">
                <img src="' . $data['i_photo'] . '" class="card-img-top" alt="demo image" height="150px">
            </div>
            <div class="col-6 bg-light card-body">
                <h5 class="bin" onclick="delete_card(this.parentNode.parentNode.parentNode,' . $data['i_id'] . ',' . $data['i_price'] . ')">
                    <span class="text-danger">
                        <svg class="icon icon-bin">
                            <use xlink:href="#icon-bin"></use>
                        </svg>
                    </span>
                </h5>
                <div class="detail">
                    <h6 class="card-subtitle"> &#8377;' . $data['i_price'] . ' </h6>
                    <p class="mt-1" style="font-size:12px;">
                        <span>' . $data['i_name'] . '</span> <br>
                        <span>' . $data['i_quantity'] . '</span>
                    </p>
                </div>
                <small>Sub-total: &#8377;<span class="i_subtotal">' . $amount . '</span></small>
                <div class="number-input">
                    <button onclick="count_down(this,' . $data['i_id'] . ',' . $data['i_price'] . ')"></button>
                    <input class="quantity" readonly min="0" name="quantity" value="' . $data['count'] . '" type="number">
                    <button onclick="count_up(this,' . $data['i_id'] . ',' . $data['i_price'] . ')" class="plus"></button>
                </div>
                </div>
        </div>
    </div>';
        }
        ?>
    </div>
</body>
<?php
require("./partial/script.php");
// require('./partial/cart_script.php');
?>
<script>
    //second nav
    let unique_items = document.getElementById('unique_items');
    let total_amount = document.getElementById('total_amount');

    unique_items.innerText = <?php echo is_int($unique_items) ? $unique_items : 0; ?>;
    total_amount.innerText = <?php echo is_int($total_amount) ? $total_amount : 0; ?>;

    //new (add to cart table)
    async function addtocart(id, operation, count) {

        let postData = {
            method: 'post',
            headers: {
                accept: 'application/text',
                'content-type': 'application/json'
            },
            body: JSON.stringify({
                "i_id": id,
                "count": count,
                "op": operation,
                "u_id": <?php echo (isset($_SESSION['userid']) ? $_SESSION['userid'] : -1); ?>
            })
        }
        let response;
        if (count == -1 && operation == "delete") {
            response = await fetch('./partial/cart_remove.php', postData);
        } else {
            response = await fetch('./partial/cart_crud.php', postData);
        }

        let fdata = await response.text();

        if (response.ok) {
            return fdata;
        } else {
            console.log('error');
        }
    }
    let body = document.querySelector('body');

    function showalert(err, desired_count, btn_addtocart, input_cart_count) {
        if (err == "false") {
            body.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger shadow a-msg" role="alert" id="logedin-alert">Sorry! Item is not added to your cart. (Might be you have not logged in.) </div>');
            // body.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger shadow a-msg" role="alert" id="logedin-alert">Sorry! Item is not added to your cart. (Might be you have not logged in.) Error: ' + err + ' </div>');

        } else {
            let db_count = err.replace(/"/g, ''); //g for global replacements
            let err_msg = db_count + ' unit(s) of this item is already in cart.';
            // let err_msg = db_count + ' unit(s) of this item is already in cart. Error: '+ err;
            if (desired_count == 1 && db_count >= 1) {
                btn_addtocart.classList.add('d-none');
                btn_addtocart.nextElementSibling.classList.remove('d-none');
                input_cart_count.value = db_count;
                // alert('already exist(insert)');
            } else if (desired_count == 0 && db_count >= 1) {
                input_cart_count.value = db_count;
                // alert('already exist(delete)');
            } else if (db_count == 0) {
                btn_addtocart.classList.remove('d-none');
                btn_addtocart.nextElementSibling.classList.add('d-none');
                input_cart_count.value = db_count;
                err_msg = 'Sorry! you have already removed this item from cart (Try again to edit).' + err;
                // alert('already exist(up/down)');
            } else {
                input_cart_count.value = db_count;
                // alert('db_count: ' + db_count + ", desired_count: " + desired_count);
            }
            body.insertAdjacentHTML('afterbegin', '<div class="alert alert-primary shadow a-msg" role="alert" id="logedin-alert">' + err_msg + '</div>');
        }
        destroyalert();
    }

    function show_delete_alert(err) {
        body.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger shadow a-msg" role="alert" id="logedin-alert">Sorry! Item is not removed from your cart. (Please try to reload the page.) </div>');
        // body.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger shadow a-msg" role="alert" id="logedin-alert">Sorry! Item is not added to your cart. (Might be you have not logged in.) Error: ' + err + ' </div>');
        destroyalert();
    }

    function destroyalert() {
        setTimeout(() => {
            var myAlert = document.getElementById("logedin-alert");
            var bsAlert = new bootstrap.Alert(myAlert);
            bsAlert.close();
        }, 5000);
    }

    let cart_count = document.getElementById('cart_count');
    <?php
    if (isset($_SESSION['logedin']) && $_SESSION['logedin'] == true) {
        $query = "select sum(count) from cart where u_id = " . $_SESSION['userid'] . "";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_array($result);
        if (!isset($data[0])) {
            echo "cart_count.innerText = 0";
        } else {
            echo "cart_count.innerText = " . $data[0] . "";
        }
    }
    ?>


    function count_down(btn, id, i_price) {
        let subtotal = btn.parentNode.parentNode.querySelector('.i_subtotal');

        if (btn.nextElementSibling.value == 1) {
            addtocart(id, "delete", 1).then((data) => {
                if (data != "true") {
                    showalert(data, 0, btn.parentNode.parentNode.querySelector('.cart'), btn.parentNode.querySelector('input[type=number]'));
                } else {
                    // btn.parentNode.querySelector('input[type=number]').classList.add('d-none');
                    subtotal.innerText = parseInt(subtotal.innerText) - i_price;
                    total_amount.innerText = parseInt(total_amount.innerText) - i_price;
                    btn.parentNode.parentNode.parentNode.parentNode.remove();
                    unique_items.innerText = parseInt(unique_items.innerText) - 1;
                    cart_count.innerText = (parseInt(cart_count.innerText) - 1);
                }
            });
            return; //delete query
        }
        let count = parseInt(btn.parentNode.querySelector('input[type=number]').value) - 1;
        addtocart(id, "down", count).then((data) => {
            if (data != "true") {
                showalert(data, count, btn.parentNode.parentNode.querySelector('.cart'), btn.parentNode.querySelector('input[type=number]'));
            } else {
                subtotal.innerText = parseInt(subtotal.innerText) - i_price;
                total_amount.innerText = parseInt(total_amount.innerText) - i_price;
                btn.parentNode.querySelector('input[type=number]').stepDown();
                // console.log(parseInt(btn.parentNode.querySelector('input[type=number]').value) + " : "+ id);//update query
                cart_count.innerText = (parseInt(cart_count.innerText) - 1);
            }
        });
    }

    function delete_card(card, id, i_price) {
        let subtotal = card.querySelector('.i_subtotal');
        addtocart(id, "delete", -1).then((data) => {
            if (data == "false") {
                show_delete_alert(data);
            } else {
                card.remove();
                let minus = 1;
                if (data != "true") {
                    minus = data.replace(/"/g, '');
                }
                subtotal.innerText = parseInt(subtotal.innerText) - (i_price * minus);
                total_amount.innerText = parseInt(total_amount.innerText) - (i_price * minus);
                cart_count.innerText = (parseInt(cart_count.innerText) - minus);
                unique_items.innerText = parseInt(unique_items.innerText) - 1;
            }
        });
    }

    function count_up(btn, id, i_price) {
        let count = parseInt(btn.parentNode.querySelector('input[type=number]').value) + 1;
        addtocart(id, "up", count).then((data) => {
            if (data != "true") {
                showalert(data, count, btn.parentNode.parentNode.querySelector('.cart'), btn.parentNode.querySelector('input[type=number]'));
            } else {
                let subtotal = btn.parentNode.parentNode.querySelector('.i_subtotal');
                subtotal.innerText = parseInt(subtotal.innerText) + i_price;
                total_amount.innerText = parseInt(total_amount.innerText) + i_price;
                btn.parentNode.querySelector('input[type=number]').stepUp();
                //console.log(parseInt(btn.parentNode.querySelector('input[type=number]').value) + " : "+ id);//update query
                cart_count.innerText = (parseInt(cart_count.innerText) + 1);
            }
        });
    }

    //  address validation ***** starts *****
    let address = document.getElementById('address');
    const addressRegEx = /^[a-zA-Z0-9,+:\s.]{50,1500}$/;
    let addressVal = address.value;

    function addressValidation() {
        addressVal = address.value;
        if (!addressRegEx.test(addressVal)) {
            address.classList.add('is-invalid');
        } else {
            address.classList.remove('is-invalid');
        }
    }
    //  address validation ***** ends *****

    function clear_addrs() {
        address.value = "";
    }
</script>


</html>