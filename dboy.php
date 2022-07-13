<?php
require('./partial/connection.php');

$LogedinAlert;

session_start();
// $_SESSION['dboylogedin'] = "lol";



if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $_SESSION['dboylogedin'] = false;
    $LogedinAlert = false;

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($username) && isset($password)) {

        $query = "select `d_id`,`d_password`, `d_active` from `deliveryboy` where `d_name` = '$username' limit 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) != 0) {

            $passwordhash;
            $uid;
            $isActive;
            foreach ($result as $foo) {
                $passwordhash = $foo['d_password'];
                $uid = $foo['d_id'];
                $isActive = $foo['d_active'];
            }
            if (password_verify($password, $passwordhash)) {

                $LogedinAlert = true;

                $_SESSION['dboylogedin'] = true;
                $_SESSION['dboyid'] = $uid;
                $_SESSION['dboy_isActive'] = $isActive;
                if (isset($_POST['keepLogedin'])) {
                    setcookie("dboyname", $username, 86400 * 30 + time(), '/');
                    // setcookie("password", $password, 86400 * 120 + time());
                    setcookie("password", $passwordhash, 86400 * 30 + time(), '/');
                }
            }
        }
    }
    // } elseif ( isset($_SESSION['dboylogedin']) && $_SESSION['dboylogedin'] == false &&  isset($_COOKIE['username']) && isset($_COOKIE['apassword'])) {
} elseif (count($_SESSION) == 0 &&  isset($_COOKIE['dboyname']) && isset($_COOKIE['apassword'])) {

    $_SESSION['dboylogedin'] = false;

    require('./partial/connection.php');

    $username = $_COOKIE['dboyname'];
    $password = $_COOKIE['apassword'];

    if (isset($username) && isset($password)) {
        $query = "select `d_id`,`d_password`, `d_active` from `deliveryboy` where `d_name` = '$username' limit 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) != 0) {
            $passwordhash;
            $uid;
            $isActive;
            foreach ($result as $foo) {
                $passwordhash = $foo['d_password'];
                $uid = $foo['d_id'];
                $isActive = $foo['d_active'];
            }

            // if (password_verify($password, $passwordhash)) {
            if ($password == $passwordhash) {

                $LogedinAlert = true;
                $_SESSION['dboylogedin'] = true;
                $_SESSION['dboyid'] = $uid;
                $_SESSION['dboy_isActive'] = $isActive;
            }
        }
    }
} else {

    if (isset($_SESSION['dboyid']) && isset($_SESSION['dboylogedin']) && $_SESSION['dboylogedin'] == true) {

        $query = "select `d_active` from deliveryboy where `d_id` = " . $_SESSION['dboyid'] . " limit 1";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);

        if (isset($data) && $data['d_active'] == 1) {
            $_SESSION['dboy_isActive'] = 1;
        } else {
            $_SESSION['dboy_isActive'] = 0;
            // header("location: dboy.php");
            // exit();
        }
    }
}

?>


<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <?php
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>

    <title>Delivery Boy Panel of GE</title>


</head>

<body>

    <?php
    require('./partial/dnav.php');
    ?>

    <?php

    if (isset($LogedinAlert)) {
        if ($LogedinAlert == true) {
            echo ' <div class="alert alert-success shadow a-msg" role="alert" id="logedin-alert">
            <b>' . $username . '</b>, You have successfully loged in.
        </div>';
        } else {
            echo '<div class="alert alert-danger shadow a-msg" role="alert" id="logedin-alert">
            Sorry! Invalid credential, Please try again to Log in.
        </div>';
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['logout']) && $_GET['logout'] == true) {
        echo ' <div class="alert alert-success shadow a-msg" role="alert" id="logedin-alert">
            You have successfully <b> loged out </b>.
        </div>';
    }


    ?>

    <?php
    if (isset($_SESSION['dboylogedin']) && $_SESSION['dboylogedin'] == true) {

        if (isset($_SESSION['dboy_isActive']) && $_SESSION['dboy_isActive'] == 0) {

            echo '<p class="display-4 text-danger text-center mt-5">Your are Not-allowed to Work...</p>';
            echo '<p class="text-warning text-center mt-5">Please Contact to Owner...</p>';
        } else {

    ?>

            <h3 class="text-primary text-center mt-1 font-weight-lighter">Today's Pending Deliveries</h3>
            <div class="m-3 table-responsive">

                <table class="table table-hover" id="myTable" class="display">
                    <thead>
                        <tr>
                            <th>Oreder Id</th>
                            <th>Name</th>
                            <th>Ph.no.</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        $query = "select o_id,o_address,u_id,o_status from orders where d_id = " . $_SESSION['dboyid'] . " and o_status != 3";
                        $result = mysqli_query($conn, $query);
                        if (is_nan(mysqli_num_rows($result)) || mysqli_num_rows($result) == 0) {
                            echo "<p class='display-4 text-primary text-center mt-5'>Booyah! All orders are delivered.</p>";
                        } else {
                            while ($data = mysqli_fetch_assoc($result)) {
                                $query1 = "SELECT u_fullname,u_phno from user where u_id = " . $data['u_id'] . "";
                                $result1 = mysqli_query($conn, $query1);
                                $data1 = mysqli_fetch_assoc($result1);
                                echo "<tr>";
                                echo "<td>" . $data['o_id'] . "</td>";
                                echo "<td>" . $data1['u_fullname'] . "</td>";
                                echo "<td>" . $data1['u_phno'] . "</td>";
                                echo "<td>" . $data['o_address'] . "</td>";
                                echo "<td>";
                                $status;
                                if ($data['o_status'] == 1) {
                                    $status = '<span class="badge rounded-pill bg-warning text-dark">Accepted</span>';
                                } elseif ($data['o_status'] == 2) {
                                    $status = '<span class="badge rounded-pill bg-info">Packed</span>';
                                } else {
                                    $status = '<span class="badge rounded-pill bg-danger">Wrong</span>';
                                }
                                echo $status;
                                echo "</td>";
                                echo "<td> <a class='btn btn-outline-danger' href='o_detail.php?o=" . $data['o_id'] . "'>Detail</a> </td>";
                                echo "</tr>";
                            }
                        }
                        ?>



                    </tbody>
                </table>

            </div>

        <?php
        }
    } else {
        ?>
        <!-- loged out code is starts from here -->
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singin-modalTitle">Sign In</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info fade show" role="alert">
                        If you don\'t have account, please click &gt; <strong> <a href="signup_dboy.php" class="text-secondary"> Sign Up </a></strong>
                    </div>
                    <form autocomplete="off" method="POST" id="dlogin" action="dboy.php">
                        <div class="form-group">
                            <input type="text" class="form-control mt-2" name="username" pattern="[a-zA-Z0-9_]+" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter user name" value="jbz">

                            <input type="password" class="form-control mt-2" name="password" id="exampleInputPassword1" placeholder="Password" value="Aa@11">

                            <input type="checkbox" class="form-check-input mt-2" id="exampleCheck1" name="keepLogedin">
                            <label class="form-check-label mt-2" for="exampleCheck1">Keep me Signned In</label>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="dlogin">
                        Sign In
                    </button>
                </div>
            </div>
        </div>
        <!-- loged out code is ends here -->
    <?php
    }
    ?>



    <?php
    require('./partial/script.php');
    require('./tools/dt/script.php');
    ?>
    <script>
        // active link highlight 
        let link = document.getElementById('home');
        link.classList.add('active');

        // pop over 
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

        /* Initialise the table with the required column ordering data types */
        $(document).ready(function() {
            $('#myTable').DataTable({
                "dom": ' <"#length"l><"#search"f>rt<"info"i><"page"p>',
                "stateSave": true
            });
        });
    </script>

    <?php

    if ((isset($LogedinAlert)) || (($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['logout']) && $_GET['logout'] == true))) {
        echo '     <script>   
                setTimeout(() => {
                    var myAlert = document.getElementById("logedin-alert");
                    var bsAlert = new bootstrap.Alert(myAlert);
                    bsAlert.close();
                }, 4000);
                </script>
                ';
    }

    ?>

</body>

</html>