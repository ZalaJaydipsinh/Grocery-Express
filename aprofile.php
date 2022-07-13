<?php
require('./partial/connection.php');

session_start();

if (isset($_SESSION['adminid'])) {
    $query = "select * from `admin` where `a_name` = '" . $_SESSION['adminid'] . "' limit 1";
    $result = mysqli_query($conn, $query);

    foreach ($result as $foo) {

        $a_name = $foo['a_name'];

        $a_password = $foo['a_password'];
    }
} else {
    header("location: admin.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <?php
    require('./partial/link.php');
    ?>
</head>

<body>
    <?php
    require('./partial/anav.php');
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
        <form class="mx-auto" action="./partial/aprofile_update.php" method="POST" autocomplete="off">
            <div class="accordion container" id="accordionExample">


                <!-- user name -->
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <label for="InputEmail" class="form-label font-weight-bold">User Name</label> <small>(Please
                            don't touch this field, if you dont want to change your User name)</small>
                        <input ondblclick="this.value=''" type="text" autocomplete="off" pattern="[a-zA-Z0-9_]+" title="You can use only a to z, A to Z, 0 to 9 and _(underscore)" onkeyup="fetchNames()" value="<?php echo $a_name; ?>" class="form-control" name="name" id="name" aria-describedby="emailHelp" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" required>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            Why? - It will be used for login purpose. so, dont forget it..
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
        // active link highlight 
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


        //submit button **** Starts ****
        let submit = document.getElementById('submit');
        submit.addEventListener('click', (e) => {



            chk_oldpasswd()

            chk_passwd()
            // update of 12/08/2020


            if (password.classList.contains('is-invalid') || oldPassword.classList
                .contains('is-invalid')) {
                e.preventDefault();
                alert("Please enter valid data.");
            }

        });
        //submit button **** Ends ****
    </script>


</body>

</html>