<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <symbol id="icon-rewind" viewBox="0 0 24 24">
            <path d="M15.343 16l5.657 5.657-2.828 2.828-8.486-8.485 8.485-8.485 2.829 2.828-5.657 5.657z"></path>
        </symbol>
    </defs>
</svg>
<nav class="navbar navbar-expand-md navbar-light bg-light foo sticky-top">

    <div class="container-fluid">

        <button class="btn text-danger" id="back_btn" onclick="history.back()">
            <svg class="icon icon-rewind">
                <use xlink:href="#icon-rewind"></use>
            </svg>
        </button>

        <button class="navbar-toggler btn-sm" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="font-size:15px;"></span>
        </button>


        <a class="navbar-brand ml-2 text-danger" href="index.php" style="line-height: 30px !important;"> G<small>rocery</small>E<small>xpress </small> <a class="navbar-brand mr-auto text-danger" href="dboy.php" style="line-height: 30px !important;margin-left: -10px"> <small> For Delivery Boys</small> </a>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-1">
                    <li class="nav-item">
                        <a class="nav-link" id="home" aria-current="page" href="dboy.php">Home</a>
                    </li>

                    <?php
                    if (isset($_SESSION['dboylogedin']) && $_SESSION['dboylogedin'] == true) {
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='profile' aria-current='page' href='./dprofile.php'>Profile</a>
                            </li>";
                        if (isset($_SESSION['dboy_isActive']) && $_SESSION['dboy_isActive'] == 1) {
                            echo "  <li class='nav-item'>
                                <a class='nav-link' id='my_deliveries' aria-current='page' href='./my_deliveries.php'>My Deliveries</a>
                            </li>";
                            echo "  <li class='nav-item'>
                            <a class='nav-link' id='all_deliveries' aria-current='page' href='./all_deliveries.php'>All Deliveries</a>
                            </li>";
                        }
                        echo "  <li class='nav-item'>
                            <a class='nav-link' id='my_deliveries' aria-current='page' href='./d_o_history.php'>Delivered History</a>
                        </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' aria-current='page' href='./partial/dlogout.php'>Log Out</a>
                            </li>";
                    } else {
                        echo '<li class="nav-item ">

                    </li>';
                    }
                    ?>
                </ul>

            </div>

    </div>

</nav>