<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Updating...</title>
    <style>
        svg {
            width: 100px;
            display: block;
            margin: 40px auto 0;
        }

        .path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 0;
        }

        .path.circle {
            -webkit-animation: dash 0.9s ease-in-out;
            animation: dash 0.9s ease-in-out;
        }

        .path.line {
            stroke-dashoffset: 1000;
            -webkit-animation: dash 0.9s 0.35s ease-in-out forwards;
            animation: dash 0.9s 0.35s ease-in-out forwards;
        }

        .path.check {
            stroke-dashoffset: -100;
            -webkit-animation: dash-check 0.9s 0.35s ease-in-out forwards;
            animation: dash-check 0.9s 0.35s ease-in-out forwards;
        }

        p {
            text-align: center;
            margin: 20px 0 60px;
            font-size: 1.25em;
        }

        p.success {
            color: #73AF55;
        }

        p.error {
            color: #D06079;
        }

        @-webkit-keyframes dash {
            0% {
                stroke-dashoffset: 1000;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes dash {
            0% {
                stroke-dashoffset: 1000;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @-webkit-keyframes dash-check {
            0% {
                stroke-dashoffset: -100;
            }

            100% {
                stroke-dashoffset: 900;
            }
        }

        @keyframes dash-check {
            0% {
                stroke-dashoffset: -100;
            }

            100% {
                stroke-dashoffset: 900;
            }
        }

        a{
            display: block;
            text-align: center;
            font-size: 1.25em;
            color: red;
        }
        a:visited{
            color: red;
        }
    </style>
</head>

<body>

    <?php

    require('connection.php');
    session_start();

    // @$id = $_POST['id'];
    @$id = $_SESSION['userid'];
    @$name = htmlentities($_POST['name']);
    @$fullname = ucwords(strtolower($_POST['fname'] . " " . $_POST['lname']));
    @$phno = $_POST['phno'];
    @$address = trim($_POST['address']);
    @$email = trim($_POST['email']);
    if (isset($_POST['password'])) {
        @$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    @$oldpassword = $_POST['oldpassword'];


    $query = "select `u_password` from `user` where `u_id` = '$id' limit 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $passwordhash;

        foreach ($result as $foo) {
            $passwordhash = $foo['u_password'];
        }

        if (password_verify($oldpassword, $passwordhash)) {

            if (isset($id) && isset($name) && isset($phno) && isset($address) && isset($email)  && isset($fullname)) {

                if (isset($password)) {
                    $query = "UPDATE `user` SET `u_fullname` = '$fullname', `u_name` = '$name', `u_password` = '$password', `u_address` = '$address', `u_phno` = '$phno', `u_email` = '$email' WHERE `u_id` = $id";

                    // log out for new password
                    session_destroy();
                    if (isset($_COOKIE['username']) && isset($_COOKIE['password']))
                    {
                        setcookie("username","");
                        setcookie("password","");
                    }
                } else {
                   
                    $query = "UPDATE `user` SET `u_fullname` = '$fullname', `u_name` = '$name', `u_address` = '$address', `u_phno` = '$phno', `u_email` = '$email' WHERE `u_id` = $id";
                    // @$password = password_hash($oldpassword, PASSWORD_DEFAULT);

                    // $query = "UPDATE `user` SET `u_fullname` = '$fullname', `u_name` = '$name', `u_password` = '$password', `u_address` = '$address', `u_phno` = '$phno', `u_email` = '$email' WHERE `u_id` = $id";
                }
            

                $result = mysqli_query($conn, $query);
                if ($result) {
                    echo '
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />
                </svg>
                <p class="success">Your account details are updated successfully</p>';
                    echo '<a href="../index.php" style="text-decoration: none;">Click here to goto Home Page</a>';
                } else {
                    echo '    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" />
                <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" />
            </svg>
            <p class="error">Something is wrong, Pleare re-update your account details</p>';
                     echo '<a href="../profile.php" style="text-decoration: none;">Click here to go back to Edit Profile.</a>';
                    echo "query error: " . mysqli_error($conn);
                }
            } else {
                echo '    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" />
                <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" />
            </svg>
            <p class="error">Something is wrong, Pleare re-update your account details</p>';
                  echo '<a href="../profile.php" style="text-decoration: none;">Click here to go back to Edit Profile.</a>';
                echo "not set error";
            }
        } else {
            echo '    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
            <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" />
            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" />
        </svg>
        <p class="error">Password is not matched, so please try agian.</p>';
        echo '<a href="../profile.php" style="text-decoration: none;">Click here to go back to Edit Profile.</a>';

        }
    }

    ?>


</body>

</html>