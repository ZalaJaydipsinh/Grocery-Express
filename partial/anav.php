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


        <a class="navbar-brand ml-2 text-danger" href="index.php" style="line-height: 30px !important;"> G<small>rocery</small>E<small>xpress </small> <a class="navbar-brand mr-auto text-danger" href="admin.php" style="line-height: 30px !important;margin-left: -10px"> <small> For Admin</small> </a>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-1">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="admin.php">Home</a>
                    </li>

                    <?php
                    if (isset($_SESSION['adminlogedin']) && $_SESSION['adminlogedin'] == true) {
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='profile' aria-current='page' href='./aprofile.php'>Profile</a>
                            </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='insert_items' aria-current='page' href='./i_insert.php'>Insert Items</a>
                            </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='items_list' aria-current='page' href='./i_list_new.php'>Items List</a>
                            </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='category' aria-current='page' href='./category.php'>Category</a>
                            </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='delivery_boys' aria-current='page' href='./dboy_list.php'>Delivery Boys List</a>
                            </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' id='feedbacks' aria-current='page' href='./feedback_list.php'>Feedbacks</a>
                            </li>";
                        echo "  <li class='nav-item'>
                                <a class='nav-link' aria-current='page' href='./partial/alogout.php'>Log Out</a>
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