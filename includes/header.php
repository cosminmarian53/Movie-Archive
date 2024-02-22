<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Movie Archive</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
  <!-- Stylesheet -->
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  const INITIALS = "LCM";
  $searchBarItems = [
    [
      'name' => 'Home',
      'link' => 'index.php'
    ],
    [
      'name' => 'Movies',
      'link' => 'movies.php'
    ],
    [
      'name' => 'Contact',
      'link' => 'contact.php'
    ]
  ]
    ?>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg nav-std">
    <div class="container-fluid">
      <a class="navbar-brand">LCM</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <ul class="navbar-nav nav-list">
          <?php
          $thispage = basename($_SERVER['PHP_SELF']);
          foreach ($searchBarItems as $item) {
            $isActive = ($item['link'] === $thispage) ? 'active' : ''; // Check if it's the current page
            echo 
          '<li class="nav-item ' . $isActive . '">
            <a class="nav-link" href="' . $item['link'] . '">' . $item['name'] . '</a>
          </li>';
          }
          ?>
        </ul>
      </div>
      <?php
      include 'search-form.php';
      ?>
    </div>
  </nav>
  <?php
  //Multidimensional array that contains all of the movie data
  $movies = json_decode(file_get_contents('./assets/movies-list-db.json'), true)['movies'];
  ?>
  <?php
  include 'functions.php';
  ?>