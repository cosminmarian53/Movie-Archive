<!-- Database connection -->
<?php
$host = 'localhost'; //database host
$dbname = 'php-proiect'; //database name
$username = 'php-user'; //database username
$password = 'php-password'; //database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If the connection is successful, log a success message to the browser console
    echo "<script>console.log('Connected to the database');</script>";
} catch (PDOException $e) {
    // If the connection fails, log an error message to the browser console
    echo "<script>console.error('Connection failed: " . $e->getMessage() . "');</script>";
}
?>

<!-- Functions -->
<?php

    function runtime_prettier($time,$format='%2d:%2d'){
        if($time<1)
        {
            return;
        }
        $hours=floor($time/60);
        $minutes=floor($time%60);
        return sprintf($format,$hours,$minutes);
    }
    function isMovieInFavorites($movieId, $favoriteMovies) {
        return in_array($movieId, $favoriteMovies);
    }
    
?>