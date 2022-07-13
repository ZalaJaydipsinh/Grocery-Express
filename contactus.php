<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <?php
    require('./partial/link.php');
    ?>
</head>

<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <symbol id="icon-phone" viewBox="0 0 32 32">
            <path d="M22 20c-2 2-2 4-4 4s-4-2-6-4-4-4-4-6 2-2 4-4-4-8-6-8-6 6-6 6c0 4 4.109 12.109 8 16s12 8 16 8c0 0 6-4 6-6s-6-8-8-6z"></path>
        </symbol>
        <symbol id="icon-envelop" viewBox="0 0 32 32">
            <path d="M29 4h-26c-1.65 0-3 1.35-3 3v20c0 1.65 1.35 3 3 3h26c1.65 0 3-1.35 3-3v-20c0-1.65-1.35-3-3-3zM12.461 17.199l-8.461 6.59v-15.676l8.461 9.086zM5.512 8h20.976l-10.488 7.875-10.488-7.875zM12.79 17.553l3.21 3.447 3.21-3.447 6.58 8.447h-19.579l6.58-8.447zM19.539 17.199l8.461-9.086v15.676l-8.461-6.59z"></path>
        </symbol>
        <symbol id="icon-location2" viewBox="0 0 32 32">
            <path d="M16 0c-5.523 0-10 4.477-10 10 0 10 10 22 10 22s10-12 10-22c0-5.523-4.477-10-10-10zM16 16.125c-3.383 0-6.125-2.742-6.125-6.125s2.742-6.125 6.125-6.125 6.125 2.742 6.125 6.125-2.742 6.125-6.125 6.125zM12.125 10c0-2.14 1.735-3.875 3.875-3.875s3.875 1.735 3.875 3.875c0 2.14-1.735 3.875-3.875 3.875s-3.875-1.735-3.875-3.875z"></path>
        </symbol>
    </defs>
</svg>

<body>
    <?php
    session_start();
    require('./partial/nav.php');

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['msg']) && $_GET['msg'] == true) {
        echo ' <div class="alert alert-success shadow a-msg" role="alert" id="logedin-alert">
                    Thank Your for giving <b> valuable feedback. </b>.
                </div>';
    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['msg']) && $_GET['msg'] == false) {
        echo ' <div class="alert alert-danger shadow a-msg" role="alert" id="logedin-alert">
                    Sorry, your feedback is not submitted due to some problem.<b> Please try later. </b>.
                </div>';
    }
    ?>
    <main class="container mt-3 col-xl-6">
        <section class="mb-4">
            <h2 class="h1-responsive font-weight-bold text-center my-4">About Us</h2>
            <p class="text-center w-responsive mx-auto mb-5">Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within amatter of hours to help you.</p>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center display-6 addresscard"><svg class="icon icon-location2">
                                    <use xlink:href="#icon-location2"></use>
                                </svg></h5>
                            <p class="card-text text-center"><span class="name">Chankya Classes, 80 feet Road, Surendranagar</span></p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center display-6 addresscard"> <svg class="icon icon-envelop">
                                    <use xlink:href="#icon-envelop"></use>
                                </svg></h5><br>

                            <p class="card-text text-center"><span class="name">groceryexpress@gmail.com</span></p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center display-6 addresscard"><svg class="icon icon-phone">
                                    <use xlink:href="#icon-phone"></use>
                                </svg></h5>
                            <br>
                            <p class="card-text text-center"><span class="name">+91 9999900000</span></p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <h2 class="h1-responsive font-weight-bold text-center my-4">Contact Us</h2>

        <?php
        if (!isset($_SESSION['userid'])) {
            echo '<h1 class="display-6 text-warning">Please log in to Contact Us</h1>
            <h6>Or you can use <b> about us </b> details to reach out to us.</h6>';
        } else {
            require('./partial/connection.php');
            $query = "select u_fullname from user where u_id = '" . $_SESSION['userid'] . "' limit 1";
            $result = mysqli_query($conn, $query);
            $data = mysqli_fetch_assoc($result);


        ?>

            <form class="mx-auto" action="./partial/feedbackcatcher.php" method="POST" autocomplete="off">
                <div class="accordion container" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <div class="row">
                                <div class="col">



                                    <label for="fname" class="form-label font-weight-bold">Your Name</label>
                                    <input type="text" class="form-control" minlength="2" maxlength="15" title="can not use special characters and white-spaces" placeholder="Your name" name="yname" id="yname" aria-label="Your name" aria-expanded="true" value="<?php echo $data['u_fullname'] ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFour">

                            <label for="InputEmail" class="form-label font-weight-bold">Any Suggestion/Feedback</label>
                            <textarea name="msg" rows="4" minlength="15" maxlength="1500" id="msg" placeholder="Your Feedback" class="form-control" required></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-danger my-2" id="submit">Submit</button>

                </div>
            </form>

        <?php
        }
        ?>

    </main>

</body>
<?php
require("./partial/script.php");
?>
<script>
    // active link highlight 
    let link = document.getElementById('contact_us');
    link.classList.add('active');
</script>

</html>