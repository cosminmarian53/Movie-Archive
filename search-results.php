<?php
$searchQuery = $_GET['search'] ?? ''; //GETs the search phrase or it sets it as an empty string
?>
<!-- Header -->
<?php include 'includes/header.php' ?>
<!-- Search form -->
<div class="container">
    <?php include 'includes/search-form.php'; ?>
</div>
<!-- Find the searched movie and check if it has 3 or more chars -->
<?php
if (isset($_GET['search']) && strlen($_GET['search']) >= 3) {
    $searchQuery = $_GET['search'];
    $searchPhrase = $_GET['search'];
    $filteredMovies = array_filter($movies, function ($movie) use ($searchPhrase) {
        return stripos($movie['title'], $searchPhrase) !== false;
    });

    ?>
    <!-- Content -->
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4"">
            <?php foreach ($filteredMovies as $movie): ?>
                <div class=" movie-card col-sm-4">
                <h1 class="mb-3">Search results for :
                    <?php echo $searchQuery ?>
                </h1>
                <div class="card card-custom id=" <?php $movie['id'] ?>>
                    <img src="<?php echo $movie['posterUrl']; ?>" class="card-img-top" alt="<?php echo $movie['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $movie['title']; ?>
                        </h5>
                        <p class="card-text">
                            <?php echo $movie['plot']; ?>
                        </p>
                        <a href="movie.php?movie_id=<?php echo $movie['id']; ?>" class="btn btn-std">Learn More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
    <?php
} else {
    // In case it doesn't find the movie or it has less than 3 chars it will ask you to try again
    echo "Please enter a search query with at least 3 characters.";
}

?>
<!-- Footer -->
<?php include 'includes/footer.php' ?>