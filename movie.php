<?php
include 'includes/header.php';

if (isset($_GET['movie_id']) && is_numeric($_GET['movie_id'])) {
    $movieId = intval($_GET['movie_id']);
    $filteredMovies = array_filter($movies, function ($movie) use ($movieId) {
        return $movie['id'] === $movieId;
    });

    if (count($filteredMovies) === 1) {
        $movie = reset($filteredMovies);

        // Check if the user submitted the form
        if (isset($_POST['favorite'])) {
            // Check if the favorite_movies cookie exists
            if (isset($_COOKIE['favorite_movies'])) {
                $favoriteMovies = json_decode($_COOKIE['favorite_movies'], true);
            } else {
                $favoriteMovies = array();
            }

            // Update the list of favorite movies based on the form submission
            $movieId = intval($_GET['movie_id']);
            if ($_POST['favorite'] === '1') {
                if (!in_array($movieId, $favoriteMovies)) {
                    $favoriteMovies[] = $movieId;
                }
            } elseif ($_POST['favorite'] === '0') {
                $key = array_search($movieId, $favoriteMovies);
                if ($key !== false) {
                    unset($favoriteMovies[$key]);
                }
            }

            // Save the updated list in the favorite_movies cookie with a one-year expiration
            setcookie('favorite_movies', json_encode($favoriteMovies), time() + 31536000);
        }

        // Load JSON content from the file
        $jsonFile = 'assets/movie-favorites.json';
        $favoriteMovies = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];

        // Assuming you have the current movie's ID in $movieId
        if (isset($_POST['favorite'])) {
            if (isset($favoriteMovies[$movieId])) {
                if ($_POST['favorite'] === '0') {
                    // If 'favorite' is 0, decrement the count
                    $favoriteMovies[$movieId]--;
                    if ($favoriteMovies[$movieId] <= 0) {
                        // Remove the entry if the count goes below or equals 0
                        unset($favoriteMovies[$movieId]);
                    }
                } else {
                    // If 'favorite' is not 0, increment the count
                    $favoriteMovies[$movieId]++;
                }
            } else {
                // Initialize with 1 if it's the first time
                $favoriteMovies[$movieId] = 1;
            }

            // Save the updated array back to the JSON file
            file_put_contents($jsonFile, json_encode($favoriteMovies, JSON_PRETTY_PRINT));

            // Redirect the user back to the same page to avoid form resubmission
            header("Location: {$_SERVER['PHP_SELF']}?movie_id=$movieId");
            exit; // Make sure to exit after a header redirect
        }

        // Handle review submissions
        if (isset($_POST['submitReview']) && !isset($_SESSION['review_submitted'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            // SQL query to insert the review into the 'reviews' table
            $sql = "INSERT INTO reviews (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);

            // Execute the query
            if ($stmt->execute()) {
                $_SESSION['review_submitted'] = true; // Mark the review as submitted in this session

                // Redirect to the same page to avoid form resubmission
                header("Location: {$_SERVER['PHP_SELF']}?movie_id=$movieId");
                exit; // Make sure to exit after a header redirect
            } else {
                echo '<div class="alert alert-danger">Error: Review submission failed.</div>';
            }
        }
        $favoriteCount = isset($favoriteMovies[$movieId]) ? $favoriteMovies[$movieId] : 0;
        ?>
        <!-- Content -->
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h1>
                        <?php echo $movie['title']; ?>
                    </h1>
                    <img class="card-img-top" src="<?php echo $movie['posterUrl']; ?>" />
                </div>
                <div class="col-sm-9 wrapper">
                    <?php if ($favoriteCount > 0) { ?>
                        <form method="POST">
                            <input type="hidden" name="favorite" value="0">
                            <button type="submit" class="btn btn-std">Șterge din favorite</button>
                        </form>
                    <?php } else { ?>
                        <form method="POST">
                            <input type="hidden" name="favorite" value="1">
                            <button type="submit" class="btn btn-std">Adaugă la favorite</button>
                        </form>
                    <?php } ?>
                    <span class="badge badge-primary">
                        <?php echo $favoriteCount; ?> favorites
                    </span>
                    <h1 class="movie-text">
                        <?php echo $movie['year']; ?>
                    </h1>
                    <p>
                        <?php echo $movie['plot']; ?><br />
                    </p>
                    <p class="movie-text">Directed by:
                        <?php echo $movie['director']; ?>
                    </p>
                    <p>Runtime:
                        <?php echo runtime_prettier($movie['runtime'], '%2d hours %2d minutes'); ?>
                    </p>
                    <p>Genres:
                        <?php echo implode(", ", $movie['genres']); ?>
                    </p>
                    <h5>Cast:</h5>
                    <ul>
                        <?php foreach (explode(", ", $movie['actors']) as $actor) { ?>
                            <li>
                                <?php echo $actor; ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Review Form -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Add a Review</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Review</label>
                            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="agree" required>
                            <label class="form-check-label" for="agree">I agree to the processing of personal data</label>
                        </div>
                        <button type="submit" name="submitReview" class="btn btn-std">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Display existing reviews -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Reviews: </h2>
                    <?php
                    // Assuming you have database connection details
                    $dbHost = 'localhost';
                    $dbName = 'local';
                    $dbUser = 'root';
                    $dbPass = 'root';

                    try {
                        // Establish a database connection
                        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

                        // Set the PDO error mode to exception
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Retrieve reviews from the 'reviews' table
                        $sql = "SELECT name, message FROM reviews";
                        $stmt = $pdo->query($sql);
                        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($reviews as $review) {
                            echo '<p><strong>' . $review['name'] . '</strong>: ' . $review['message'] . '</p>';
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    } finally {
                        // Close the database connection (optional)
                        $pdo = null;
                    }
                    ?>
                </div>
            </div>
        </div>


        <!-- Last case: no movie was found -->
        <?php
    } else {
        ?>
        <!-- Movie not found -->
        <p>Filmul nu a fost găsit.</p>
        <a href="movies.php">Înapoi la filme</a>
    <?php }
} else {
    ?>
    <!-- Invalid or missing "movie_id" parameter -->
    <p>Parametrul "movie_id" lipsește sau nu este valid.</p>
    <a href="movies.php">Înapoi la filme</a>
<?php } ?>

<?php
include 'includes/footer.php';
?>