<?php
  include 'includes/header.php';
?>

  <!-- Content -->
  <div class="container">
    <h1 class="mb-3">Movies</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php
      $i = 1;
      // Loop through the movies array and generate movie cards
      foreach ($movies as $movie) {
        include 'includes/archive-movie.php';
      }
      ?>
    </div>
  </div>

<?php
  include 'includes/footer.php';
?>