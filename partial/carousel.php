<div id="Gcarousel" class="carousel slide" data-ride="carousel">

  <ol class="carousel-indicators">
    <li data-target="#Gcarousel" data-slide-to="0" class="active"></li>
    <?php
    for ($i = 1; $i <= 4; $i++) {
      echo '<li data-target="#Gcarousel" data-slide-to="' . $i . '"></li>';
    }

    ?>
  </ol>

  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="./pics/carousel/1.jpg" class="d-block w-100">
    </div>
    <?php
    for ($i = 2; $i <= 5; $i++) {
      echo '<div class="carousel-item">
        <img src="./pics/carousel/' . $i . '.jpg" class="d-block w-100">
      </div>';
    }

    ?>
  </div>

  <a class="carousel-control-prev" href="#Gcarousel" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#Gcarousel" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>

</div>