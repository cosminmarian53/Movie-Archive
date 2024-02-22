<div class="movie-card col-sm-4">
    <div class="card card-custom h-100" id="<?php $movie['id'] ?>">
        <img src="<?php echo $movie['posterUrl'] ?>" class="card-img-top" alt="<?php echo $movie['title'] . "_movie_poster"?>"/>
        <div class="card-body">
            <h5 class="card-title"><b> <?php echo $movie['title'] ?></b></h5>
            <?php
            // Check if the movie plot has more or less than 100 chars
            if($movie['plot']>100)
            {
                $plot=substr($movie['plot'],0,100) . '...';
            }else{
                $plot=$movie['plot'];
            }
            ?>
            <p class="card-text"> <?php echo $plot ?></p>
            <a  href="movie.php?movie_id= <?php echo $movie['id'] ?>" class="btn btn-std">Learn More</a>
        </div>
    </div>
</div>

<?php $i++; ?>