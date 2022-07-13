<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    
</head>

<body>

    <?php

    require('connection.php');

    require('error_msg.php');


    @$name = $_POST['name'];
    @$fullname = ucwords(strtolower($_POST['fname'] . " " . $_POST['lname']));
    @$phno = $_POST['phno'];
    @$address = htmlentities(trim($_POST['address']));
    @$email = trim($_POST['email']);
    @$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    if (isset($name) && isset($phno) && isset($address) && isset($email) && isset($password) && isset($fullname)) {
        $query =  "INSERT INTO `user` (`u_id`,`u_fullname`, `u_name`, `u_address`, `u_phno`, `u_email`, `u_password`, `u_join_date`) VALUES ( NULL,'$fullname', '$name', '$address', '$phno', '$email', '$password', CURRENT_TIMESTAMP )";

        $result = mysqli_query($conn, $query);
        if ($result) {
            success_msg("Your account is created successfully","../index.php","Home");
        } else {
            failure_msg("Something is wrong, Pleare re-create your account","../signup.php","Sign Up");
        }
    } else {
        failure_msg("Something is wrong, Pleare re-create your account. (var is not set)","../signup.php","Sign Up");
    }
    ?>



</body>

</html>