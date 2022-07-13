<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <?php
    // require('./testing/new style.php');
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>
    <style>
        /* .carousel-inner>.item>img {
            min-height: 300px;
            max-height: 300px;
            width: 100%;
        }
        .carousel{
            height: 300px;
            width: 1000px;
        } */
    </style>


</head>

<body>
    <?php
    require("./partial/nav.php");

    ?>
    <div class="container">
    hello
        <div id="carousel" class="carousel slide" data-ride="carousel">

            <ol class="carousel-indicators">
                <li data-target="#carousel" data-slide-to="0" class="active"></li>
                <?php
                for ($i = 1; $i <= 4; $i++) {
                    echo '<li data-target="#carousel" data-slide-to="' . $i . '"></li>';
                }

                ?>
            </ol>

            <div class="carousel-inner">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo '<div class="carousel-item">
      <img src="./pics/carousel/' . $i . '.jpg" class="d-block w-100" alt="carousel img">
    </div>';
                }

                ?>
            </div>

            <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>
        hi
    </div>
    

</body>
<?php
require("./partial/script.php");
?>

<script>
var myCarousel = document.querySelector('#myCarousel')
var carousel = new bootstrap.Carousel(myCarousel)
</script>


</html>