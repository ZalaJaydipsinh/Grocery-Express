<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>
    <title>Feedbacks (GE)</title>

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

    if ($_SERVER['REQUEST_METHOD'] == "GET" && count($_GET) >= 1) {
        $msg;
        if (isset($_GET['d']) && $_GET['d'] == true) {
            $msg = "Item is deleted successfully.";
        } else {
            $msg = "Hope you are enjoing <b>Grocery Express</b> ";
        }
        echo '<div class="alert alert-success shadow a-msg" role="alert" id="alert-msg">
    ' . $msg . '
            </div>';

        echo '     <script>   
        setTimeout(() => {
            var myAlert = document.getElementById("alert-msg");
            var bsAlert = new bootstrap.Alert(myAlert);
            bsAlert.close();
        }, 5000);
        </script>
        ';
    }
    ?>




    <div class="m-3 table-responsive">

        <?php
        $query = "SELECT f_id,u_fullname,u_phno,feedback FROM feedback join user on feedback.u_id = user.u_id";
        $result = mysqli_query($conn, $query);
        if (is_nan(mysqli_num_rows($result)) || mysqli_num_rows($result) == 0) {
            echo "<p class='display-4 text-primary text-center mt-5'>Not Any Feedback is submited.</p>";
        } else {
        ?>

            <table class="table table-hover" id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Name</>
                        <th>Ph.no</th>
                        <th>Feedback</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>


                    <?php

                    while ($data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        // echo "<td> <a type='button' href='./i_update.php?update=" . $data['i_id'] . "' class='btn btn-outline-primary edit'>Edit</a> </td>"; //edit
                        echo "<td>" . $data['u_fullname'] . "</td>";
                        echo "<td>" . $data['u_phno'] . "</td>";
                        echo "<td>" . $data['feedback'] . "</td>";
                        echo "<td> <a type='button' onclick='return delete_confirm()' href='./partial/f_delete.php?delete=" . $data['f_id'] . "' class='btn btn-outline-danger'>Delete</a> </td>"; //delete
                        echo "</tr>";
                    }

                    ?>



                </tbody>
            </table>
        <?php
        }
        ?>
    </div>

    <?php
    require('./partial/script.php');
    require('./tools/dt/script.php');
    ?>


    <script>
        function delete_confirm() {
            if (confirm("Are you sure to delete it.")) {
                return true;
            } else {
                return false;
            }
        }


        $(document).ready(function() {
            $('#myTable').DataTable({
                "columns": [{
                    "width": "20%"
                }, {
                    "width": "20%"
                }, {
                    "width": "50%"
                }, {
                    "width": "10%"
                }],
                "dom": ' <"#length"l><"#search"f>rt<"info"i><"page"p>',
                "stateSave": true
            });
        });


        // active link highlight 
        let link = document.getElementById('feedbacks');
        link.classList.add('active');

        // pop over 
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    </script>

</body>

</html>