<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>
    <title>Delivery Boy List (GE)</title>

</head>

<body>

    <?php
    session_start();
    require('./partial/anav.php');
    require('./partial/connection.php');
    if (!isset($_SESSION['adminlogedin']) && $_SESSION['adminlogedin'] == false) {
        header("location: admin.php");
        exit();
    }
    ?>

    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">oops! Something went wrong.</h5>
                </div>
                <div class="modal-body">
                    <b id="dboyName"></b> is not <i id="dboyActiveStatus"></i>.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Ok! I will do it again.</button>
                </div>
            </div>
        </div>
    </div>


    <div class="m-3 table-responsive">

        <table class="table table-hover" id="myTable" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Ph. No.</th>
                    <th>Email</th>
                    <th>Deliveries</th>
                    <th>Joining Date</th>
                    <th>Activation</th>
                </tr>
            </thead>
            <tbody>


                <?php
                $query = "select * from deliveryboy";
                $result = mysqli_query($conn, $query);
                if (is_nan(mysqli_num_rows($result)) || mysqli_num_rows($result) == 0) {
                    echo "<p class='display-4 text-primary text-center mt-5'>Not Any Delivery Boy is registered.</p>";
                } else {
                    while ($data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $data['d_fullname'] . "</td>";
                        echo "<td>" . $data['d_phno'] . "</td>";
                        echo "<td>" . $data['d_email'] . "</td>";
                        echo "<td>" . $data['d_orders'] . "</td>";
                        echo "<td>" . $data['d_join_date'] . "</td>";
                        echo "<td><div class='form-check form-switch'>";
                        //     echo "<input class='form-check-input mx-auto' id='" . $data['d_id'] . "' onclick='activation(this,\"" . $data['d_fullname'] . "\")' type='checkbox' ". ($data['d_active'] == 1) ? 'checked' : '' ." >";
                ?>
                        <input type="checkbox" class="form-check-input mx-auto" id="<?php echo $data['d_id']; ?>" onclick='activation(this,"<?php echo $data['d_fullname']; ?>")' <?php
                                                                                                                                                                                    if ($data['d_active'] == 1) {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>
                <?php
                        echo "</td></div></tr>";
                    }
                }
                ?>



            </tbody>
        </table>

    </div>

    <?php
    require('./partial/script.php');
    require('./tools/dt/script.php');
    ?>


    <script>
        $.fn.dataTable.ext.order['dom-checkbox'] = function(settings, col) {
            return this.api().column(col, {
                order: 'index'
            }).nodes().map(function(td, i) {
                return $('input', td).prop('checked') ? '1' : '0';
            });
        }

        /* Initialise the table with the required column ordering data types */
        $(document).ready(function() {
            $('#myTable').DataTable({
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                "dom": ' <"#length"l><"#search"f>rt<"info"i><"page"p>',
                "stateSave": true
            });
        });
        // active link highlight 
        let link = document.getElementById('delivery_boys');
        link.classList.add('active');

        // pop over 
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

        async function sendActivation(dboyId, active) {
            let postData = {
                method: 'post',
                headers: {
                    accept: 'application/text',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    "dboyId": dboyId,
                    "active": active
                })
            }

            let response = await fetch('./partial/dboy_activation.php', postData);

            let fdata = await response.text();

            if (response.ok) {
                return fdata;
            } else {
                console.log('error');
            }
        }
        let alertMsg = new bootstrap.Modal(document.getElementById('exampleModal'), {
            backdrop: 'static',
            keyboard: false
        });
        let dboyName = document.getElementById('dboyName');
        let dboyActiveStatus = document.getElementById('dboyActiveStatus');

        function activation(x, name) {
            dboyName.innerText = name;

            if (x.checked) {
                sendActivation(x.id, 1).then((data) => {
                    //console.log(data);
                    dboyActiveStatus.innerText = "Activated";
                    if (data == "false") {
                        alertMsg.show();
                        x.checked = false;
                    }

                });
            } else {
                sendActivation(x.id, 0).then((data) => {
                    //console.log(data);
                    dboyActiveStatus.innerText = "Deactivated";
                    if (data == "false") {
                        alertMsg.show();
                        x.checked = true;
                    }
                });
            }
        }
    </script>

</body>

</html>