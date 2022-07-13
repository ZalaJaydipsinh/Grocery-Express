<script>
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

        let response = await fetch('./partial/cart_crud.php', postData);

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
    //cart buttons
    function cart_btn(btn, id) {
        addtocart(id, "insert", 1).then((data) => {
            if (data != "true") {
                showalert(data, 1, btn, btn.nextElementSibling.querySelector('input[type=number]'));
            } else {
                btn.classList.add('d-none');
                btn.nextElementSibling.classList.remove('d-none');
                cart_count.innerText = (parseInt(cart_count.innerText) + 1);
                //console.log(btn.nextElementSibling.querySelector('input[type=number]').value + " : "+ id);//insert query
            }
        });
    }

    function count_down(btn, id) {
        if (btn.nextElementSibling.value == 1) {
            addtocart(id, "delete", 1).then((data) => {
                if (data != "true") {
                    showalert(data, 0, btn.parentNode.parentNode.querySelector('.cart'), btn.parentNode.querySelector('input[type=number]'));
                } else {
                    btn.parentNode.classList.add('d-none');
                    btn.parentNode.parentNode.querySelector('.cart').classList.remove('d-none');
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
                btn.parentNode.querySelector('input[type=number]').stepDown();
                // console.log(parseInt(btn.parentNode.querySelector('input[type=number]').value) + " : "+ id);//update query
                cart_count.innerText = (parseInt(cart_count.innerText) - 1);
            }
        });
    }

    function count_up(btn, id) {
        let count = parseInt(btn.parentNode.querySelector('input[type=number]').value) + 1;
        addtocart(id, "up", count).then((data) => {
            if (data != "true") {
                showalert(data, count, btn.parentNode.parentNode.querySelector('.cart'), btn.parentNode.querySelector('input[type=number]'));
            } else {
                btn.parentNode.querySelector('input[type=number]').stepUp();
                //console.log(parseInt(btn.parentNode.querySelector('input[type=number]').value) + " : "+ id);//update query
                cart_count.innerText = (parseInt(cart_count.innerText) + 1);
            }
        });
    }
</script>