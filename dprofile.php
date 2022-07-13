<?php
require('./partial/connection.php');

session_start();

if (isset($_SESSION['dboyid'])) {
    $query = "select * from `deliveryboy` where `d_id` = '" . $_SESSION['dboyid'] . "' limit 1";
    $result = mysqli_query($conn, $query);

    foreach ($result as $foo) {
        $u_id = $foo['d_id'];
        $u_fullname = $foo['d_fullname'];
        $u_name = $foo['d_name'];

        $u_phno = $foo['d_phno'];
        $u_email = $foo['d_email'];
        $u_password = $foo['d_password'];
    }
    $u_fname = explode(" ", $u_fullname)[0];
    $u_lname = explode(" ", $u_fullname)[1];
} else {
    header("location: dboy.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Boy Profile</title>
    <?php
    require('./partial/link.php');
    ?>
</head>

<body>
    <?php
    require('./partial/dnav.php');
    ?>
    <div class="bg-dark text-light">

    </div>
    <main class="container mt-3 col-md-6">

        <div class="alert alert-success" role="alert">
            You can change your profile.<br>

            <small>Double Click on any input field to Clear Data</small>
        </div>
        <div class="alert alert-warning" role="alert">
            Note. Changes will be permanent <small>(Possible to change it again from here)</small> , cant be undo. So be
            carefull while changing this details.
        </div>
        <form class="mx-auto" action="./partial/dprofile_update.php" method="POST" autocomplete="off">
            <div class="accordion container" id="accordionExample">

                <!-- <input type="text" name="id" value="<?php //echo $u_id; 
                                                            ?>" hidden> -->

                <!-- first - last name  -->
                <div class="card">
                    <div class="card-header" id="headingOne">

                        <div class="row">
                            <div class="col">
                                <label for="fname" class="form-label font-weight-bold">Name</label>
                                <input ondblclick="this.value=''" type="text" value="<?php echo $u_fname; ?>" class="form-control" onkeyup="fnameValidation()" minlength="3" maxlength="15" pattern="[a-zA-Z]+" title="can not use special characters and white-spaces" placeholder="First name" name="fname" id="fname" aria-label="First name" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" required>
                            </div>
                            <div class="col">
                                <label for="lname" class="form-label font-weight-bold">Surname</label>
                                <input ondblclick="this.value=''" type="text" value="<?php echo $u_lname; ?>" class="form-control" onkeyup="lnameValidation()" minlength="3" maxlength="15" pattern="[a-zA-Z]+" title="can not use special characters and white spaces" placeholder="Last name" name="lname" id="lname" aria-label="Last name" required>
                            </div>
                        </div>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - This name will be printed in your order.
                        </div>
                    </div>
                </div>
                <!-- user name -->
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <label for="InputEmail" class="form-label font-weight-bold">User Name</label> <small>(Please
                            don't touch this field, if you dont want to change your User name)</small>
                        <input ondblclick="this.value=''" type="text" autocomplete="off" pattern="[a-zA-Z0-9_]+" title="You can use only a to z, A to Z, 0 to 9 and _(underscore)" onkeyup="fetchNames()" value="<?php echo $u_name; ?>" class="form-control" name="name" id="name" aria-describedby="emailHelp" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" required>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - It will be used for login purpose. so, dont forget it..
                        </div>
                    </div>
                </div>
                <!-- phone number -->
                <div class="card">
                    <div class="card-header" id="headingThree">

                        <label for="InputEmail" class="form-label font-weight-bold">Phone Number</label>
                        <input onkeyup="phnoValidation()" ondblclick="this.value=''" type="text" pattern="[0-9]{10}" name="phno" title="Please Enter Valid Phone Number" value="<?php echo $u_phno; ?>" class="form-control" id="phno" aria-describedby="emailHelp" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" required>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - Our delivery boy can contact you.
                        </div>
                    </div>
                </div>
                <!-- Email Address -->
                <div class="card">
                    <div class="card-header" id="headingFive">

                        <label for="InputEmail" class="form-label font-weight-bold">Email address</label>
                        <input onkeyup="emailValidation()" ondblclick="this.value=''" type="email" value="<?php echo $u_email; ?>" class="form-control" name="email" id="email" aria-describedby="emailHelp" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" required>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - We will provide usefull information on this email id.
                        </div>
                    </div>
                </div>
                <!-- Password -->
                <div class="card">
                    <div class="card-header" id="headingOldPasswd">

                        <label for="InputPassword" class="form-label font-weight-bold">Password</label>
                        <input type="password" onkeyup="chk_oldpasswd()" class="form-control" name="oldpassword" id="oldpassword" data-toggle="collapse" data-target="#collapseOldPasswd" aria-expanded="false" aria-controls="collapseOldPasswd" required>
                    </div>
                    <div id="collapseOldPasswd" class="collapse" aria-labelledby="headingOldPasswd" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - Required to insert password to change the details.
                        </div>
                    </div>
                </div>
                <!-- New - Password -->
                <div class="card">
                    <div class="card-header" id="headingSix">

                        <div class="form-check form-switch">
                            <input class="form-check-input" id="passwdEnable" onclick="newpasswd()" type="checkbox" id="flexSwitchCheckChecked">
                            <label class="form-check-label" for="flexSwitchCheckChecked">If you want to change password,
                                enable it.</label>
                        </div>

                        <br>

                        <label for="InputPassword" class="form-label font-weight-bold">New Password</label>
                        <input ondblclick="this.value=''" type="password" onkeyup="chk_passwd()" minlength="4" maxlength="15" class="form-control" name="password" id="password" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix" disabled>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - It will required to login.
                        </div>
                    </div>
                </div>
                <!-- Re enter New-Password -->
                <div class="card">
                    <div class="card-header" id="headingSeven">

                        <label for="InputPassword" class="form-label font-weight-bold">Re-enter above
                            New-Password</label>
                        <input ondblclick="this.value=''" type="password" onkeyup="chk_passwd()" title="4 to 15" class="form-control" name="re-password" id="rePassword" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven" disabled>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - Check weather you set your desired password or you may enterd something unwanted.
                        </div>
                    </div>
                </div>


                <!-- <button class="btn btn-outline-success my-2" onclick="location.reload()">Reload All Fields</button> -->
                <button type="reset" class="btn btn-outline-success my-2">Reload All Fields</button>
                <button type="submit" class="btn btn-outline-danger my-2 ml-4" id="submit">Save Changes
                    Permanently</button>
            </div>
        </form>
    </main>

    <?php
    require('./partial/script.php');
    ?>

    <script>
        let link = document.getElementById('profile');
        link.classList.add('active');

        let passwdEnable = document.getElementById("passwdEnable");

        function newpasswd() {
            if (passwdEnable.checked) {
                document.getElementById('rePassword').disabled = false;
                document.getElementById('password').disabled = false;
            } else {
                document.getElementById('rePassword').disabled = true;
                document.getElementById('password').disabled = true;
            }
        }

        // fname validation ***** starts *****
        let fname = document.getElementById('fname');
        const fnameRegEx = /^[a-zA-Z]{3,15}$/; //can not use special characters and white-spaces

        function fnameValidation() {
            fnameVal = fname.value;
            if (!fnameRegEx.test(fnameVal)) {
                fname.classList.add('is-invalid');
            } else {
                fname.classList.remove('is-invalid');
            }
        }
        // fname validation ***** ends *****


        // lname validation ***** starts *****
        let lname = document.getElementById('lname');
        const lnameRegEx = /^[a-zA-Z]{3,15}$/; //can not use special characters and white-spaces

        function lnameValidation() {
            lnameVal = lname.value;
            if (!lnameRegEx.test(lnameVal)) {
                lname.classList.add('is-invalid');
            } else {
                lname.classList.remove('is-invalid');
            }
        }
        // lname validation ***** ends *****


        // phno validation ***** starts *****
        let phno = document.getElementById('phno');
        const phnoRegEx = /^\d{10}$/; //Please Enter Valid Phone Number

        function phnoValidation() {
            phnoVal = phno.value;
            if (!phnoRegEx.test(phnoVal)) {
                phno.classList.add('is-invalid');
            } else {
                phno.classList.remove('is-invalid');
            }
        }
        // phno validation ***** ends *****


        // email validation ***** starts *****
        let email = document.getElementById('email');
        const emailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        function emailValidation() {
            emailVal = email.value;
            if (!emailRegEx.test(emailVal)) {
                email.classList.add('is-invalid');
            } else {
                email.classList.remove('is-invalid');
            }
        }
        // email validation ***** ends *****

        //  password validation ***** starts *****
        let password = document.getElementById('password');
        let rePassword = document.getElementById('rePassword');
        const passwordRegEx =
            /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{5,15}$/; //with at least a symbol, upper and lower case letters and a number (5,15) characters long

        function passwordValidation() {
            passwordVal = password.value;
            if (!passwordRegEx.test(passwordVal)) {
                password.classList.add('is-invalid');
            } else {
                password.classList.remove('is-invalid');
            }

        }

        function chk_passwd() {
            if (passwdEnable.checked) {
                passwordValidation()
                if (password.value != rePassword.value) {
                    rePassword.classList.add('is-invalid');
                } else {
                    rePassword.classList.remove('is-invalid');
                }
            }
        }

        let oldPassword = document.getElementById('oldpassword');

        function chk_oldpasswd() {
            oldPasswordVal = oldPassword.value;
            if (!passwordRegEx.test(oldPasswordVal)) {
                // oldPassword.classList.add('is-invalid');  //!!!!!! needed to be uncomment !!!!!!!
            } else {
                oldPassword.classList.remove('is-invalid');
            }
        }
        //  password validation ***** ends *****

        //  name validation ***** Starts *****
        let name = document.getElementById('name');
        const nameRegEx = /[a-zA-Z0-9_]{2,25}/;
        let dbData;

        async function phpFetchNames() {
            let name = document.getElementById('name');
            let postData = {
                method: 'post',
                headers: {
                    accept: 'application/text',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    "name": name.value
                })
            }

            let response = await fetch('./partial/signup_dname_check.php', postData);

            let fdata = await response.text();

            if (response.ok) {
                return fdata;
            } else {
                console.log('error');
            }
        }

        let orgName = "<?php echo $u_name; ?>";
        let nameVal = name.value;

        function fetchNames() {
            phpFetchNames().then((data) => {
                dbData = data;
                nameVal = name.value;

                if (nameVal == orgName) {
                    dbData = "false";
                }
                nameValidation();
            });
        }

        // phpFetchNames().then((data) => {
        //     dbData = data;
        // });

        function nameValidation() {
            if (!nameRegEx.test(nameVal) || (dbData == "true")) {
                name.classList.add('is-invalid');
            } else {
                name.classList.remove('is-invalid');
            }
        }
        //  name validation ***** ends *****


        //submit button **** Starts ****
        let submit = document.getElementById('submit');
        submit.addEventListener('click', (e) => {

            // update of 12/08/2020
            fetchNames()

            fnameValidation()
            lnameValidation()
            phnoValidation()
            emailValidation()
            chk_oldpasswd()
            // passwordValidation()

            chk_passwd()
            // update of 12/08/2020

            let name = document.getElementById('name');
            if (name.classList.contains('is-invalid')) {
                e.preventDefault();
                alert("User Name: " + name.value + " is already exist, please write unique User Name.");
            }
            if (rePassword.classList.contains('is-invalid')) {
                e.preventDefault();
                alert("Your re-entered password is does not matches.");
            }
            if (fname.classList.contains('is-invalid') || lname
                .classList.contains('is-invalid') || phno.classList.contains('is-invalid') || email.classList
                .contains('is-invalid') || password.classList.contains('is-invalid') || oldPassword.classList
                .contains('is-invalid')) {
                e.preventDefault();
                alert("Please enter valid data.");
            }

        });
        //submit button **** Ends ****
    </script>


</body>

</html>