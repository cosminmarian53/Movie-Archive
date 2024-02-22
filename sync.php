<?php
define( 'POST_TYPE', 'my_movies' );
define( 'RUNTIME', 'my_runtime' );
define( 'TAXONOMY_YEARS', 'my_years' );
define( 'TAXONOMY_GENRES', 'my_genres' );
define( 'POST_TYPE_ACTORS', 'my_actors' );
define( 'POST_TYPE_DIRECTORS', 'my_directors' );
define( 'MOVIE_ACTORS_CONNECTION', 'movies_to_actors' );
define( 'MOVIE_DIRECTORS_CONNECTION', 'movies_to_directors' );
define( 'JSON_LOCATION', 'movies-list-db.json' );


ini_set('memory_limit','40000M');
ini_set('max_execution_time', 0);
set_time_limit(0);
ignore_user_abort(true);

try {

	define( 'WP_MAX_MEMORY_LIMIT', '4096M' );
	define('WP_USE_THEMES', false);
	require_once( '../wp-load.php');

  $genres = json_decode(file_get_contents(JSON_LOCATION))->genres;
  $movies = json_decode(file_get_contents(JSON_LOCATION))->movies;

	get_movies($genres, $movies);

	if (function_exists("wp_cache_clear_cache")){
		wp_cache_clear_cache();
	}

} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}

function get_movies($genres, $movies){

	if(isset($_GET['movie_id'])){
		$movie_no = $_GET['movie_id'] - 1;
		insert_or_update($movies[$movie_no]);
	}else{

		$counter = count($movies);

		if(empty($counter))
		return;

		foreach($movies as $movie){
			insert_or_update($movie);
		}

  	}
}

function insert_or_update($movie) {

	$args = array(
		'name' => sanitize_title($movie->title),
		'post_type'      => POST_TYPE,
		'post_status'    => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
		'posts_per_page' => 1,
		'suppress_filters' => false
	);

	$movie_obj = get_posts( $args);
	$movie_ID = '';

	if ( $movie_obj ){
		$movie_ID = $movie_obj[0]->ID;
	}

	$movie_args = array(
		'ID'            => $movie_ID,
		'post_title'    => $movie->title,
		'post_type'     => POST_TYPE,
		'post_status'   => 'publish',
		'post_content' 	=> $movie->plot,
		'post_name' 	=> sanitize_title($movie->title),
	);

	$movie_ID = wp_insert_post( $movie_args );


	if ( $movie_ID ) {

		//runtime
		update_field( RUNTIME, $movie->runtime, $movie_ID );



		if($title = $movie->year){
			$term = get_term_by('slug', strval(sanitize_title($title)), TAXONOMY_YEARS);

			if(!$term || !isset($term) || empty($term)){
				$term = (object) wp_insert_term(
					$title,
					TAXONOMY_YEARS,
					array(
						'slug' => strval(sanitize_title($title))
					)
				);
			}
			wp_set_post_terms( $movie_ID, $term->term_id, TAXONOMY_YEARS, false );
			unset($term);
			unset($title);
		}else{
			wp_delete_object_term_relationships( $movie_ID, TAXONOMY_YEARS );
		}

		if($movie->genres){
			foreach($movie->genres as $genre){

				$term = get_term_by('slug', strval(sanitize_title($genre)), TAXONOMY_GENRES);
						//echo '<br><br><br> BEFORE:';print_r($term);
				if(!$term || !isset($term) || empty($term)){
						//echo '<br><br> TITLE: ';print_r(strval(sanitize_title($genre)));
					$term = (object) wp_insert_term(
						$genre,
						TAXONOMY_GENRES,
						array(
							'slug' => strval(sanitize_title($genre))
						)
					);
				}
					//echo '<br><br> TERM->ID: ';print_r($term->term_id);
					//echo '<br><br> AFTER: ';print_r($term);
				wp_set_post_terms( $movie_ID, $term->term_id, TAXONOMY_GENRES, true );

				unset($genre);
				unset($term);
			}
		}else{
			wp_delete_object_term_relationships( $movie_ID, TAXONOMY_GENRES );
		}


		if($movie->actors){
			$actors_arr = explode(",", $movie->actors);

			foreach($actors_arr as $actor){
				$args = array(
					'name' 				=> sanitize_title($actor),
					'post_type'      	=> POST_TYPE_ACTORS,
					'post_status'    	=> array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
					'posts_per_page' 	=> 1,
					'suppress_filters' 	=> false
				);

				$actor_obj = get_posts( $args);

				$actor_ID = '';

				if ( $actor_obj ){
					$actor_ID = $actor_obj[0]->ID;
				}

				$args = array(
					'ID'            => $actor_ID,
					'post_title'    => $actor,
					'post_type'     => POST_TYPE_ACTORS,
					'post_status'   => 'publish',
					'post_name' 	=> sanitize_title($actor),
				);

				$actor_ID = wp_insert_post( $args );


				if ( $actor_ID ) {
					MB_Relationships_API::add( $movie_ID, $actor_ID, MOVIE_ACTORS_CONNECTION );
					/*p2p_type( MOVIE_ACTORS_CONNECTION )->connect($actor_ID, $movie_ID, array(
						'date' => current_time('mysql')
					) );*/
				}
			}
		}

		if($movie->director){
			$directors_arr = explode(",", $movie->director);

			foreach($directors_arr as $director){
				$args = array(
					'name' 				=> sanitize_title($director),
					'post_type'      	=> POST_TYPE_DIRECTORS,
					'post_status'    	=> array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
					'posts_per_page' 	=> 1,
					'suppress_filters' 	=> false
				);

				$director_obj = get_posts( $args);

				$director_ID = '';

				if ( $director_obj ){
					$director_ID = $director_obj[0]->ID;
				}

				$args = array(
					'ID'            => $director_ID,
					'post_title'    => $director,
					'post_type'     => POST_TYPE_DIRECTORS,
					'post_status'   => 'publish',
					'post_name' 	=> sanitize_title($director),
				);

				$director_ID = wp_insert_post( $args );


				if ( $director_ID ) {
					MB_Relationships_API::add( $movie_ID, $director_ID, MOVIE_DIRECTORS_CONNECTION );
					/*p2p_type( MOVIE_DIRECTORS_CONNECTION )->connect($director_ID, $movie_ID, array(
						'date' => current_time('mysql')
					) );*/
				}
			}
		}


		// poster featured image
		$image = $movie->posterUrl;
		if($image && strlen($image)){
			$file_headers = @get_headers($image);
			if(!$file_headers || $file_headers[0] == 'HTTP/1.1 200 OK') {
				/*
				// error message for file not found in directory
				$message = $image.' is not available or found in directory.';

				// does image file exist in directory
				if(file_exists($image)){
					//prepare upload image to WordPress Media Library
					$upload = wp_upload_bits($image , null, file_get_contents($image, FILE_USE_INCLUDE_PATH));
					// check and return file type
					$imageFile = $upload['file'];
					$wpFileType = wp_check_filetype($imageFile, null);
					// Attachment attributes for file
					$attachment = array(
					'post_mime_type' => $wpFileType['type'],  // file type
					'post_title' => sanitize_file_name($imageFile),  // sanitize and use image name as file name
					'post_content' => '',  // could use the image description here as the content
					'post_status' => 'inherit'
					);
					// insert and return attachment id
					$attachmentId = wp_insert_attachment( $attachment, $imageFile, $postId );
					// insert and return attachment metadata
					$attachmentData = wp_generate_attachment_metadata( $attachmentId, $imageFile);
					// update and return attachment metadata
					wp_update_attachment_metadata( $attachmentId, $attachmentData );
					// finally, associate attachment id to post id
					$success = set_post_thumbnail( $movie_ID, $attachmentId );
					// was featured image associated with post?
					if($success){
					$message = $image.' for post '.$movie_ID.' has been added as featured image to post.';
					} else {
					$message = $image.' for post '.$movie_ID.' has NOT been added as featured image to post.';
					}
				}
				echo $message;
				*/

				//UPLOAD IF FILE DOESN'T ALREADY EXIST
				$post_name = basename($image, ".jpg");

				preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $image, $matches);    // fix file filename for query strings
				$url_filename = basename($matches[0]);
				$url_type = wp_check_filetype($url_filename);
				$post_name = basename($url_filename, "." . $url_type['ext']);
				$attachment = wp_get_attachment_by_post_name( $post_name );

				if ( $attachment ) {
					$image_id = $attachment->ID;
				}else{
					$image_id = somatic_attach_external_image($image, $movie_ID, null, array('post_excerpt' => $movie->title));
				}
				set_post_thumbnail($movie_ID, $image_id);
			}
		}

		/*
		//checking if there are different files on the server (attached to this property) than on feed and deleting them
		$media = get_attached_media( 'image' , $movie_ID);

		$pluck = wp_list_pluck( $media, 'post_title', $index_key = null );

		$pluckReset = array_values($pluck);

		$differentFilesOnServer = array_values(array_diff($pluckReset, $attachmentsToSync_names));

		for($i=0; $i < count($differentFilesOnServer); $i++){
			$attachmentid = array_search($differentFilesOnServer[$i],$pluck);
			if($attachmentid){
				$result = wp_delete_attachment( $attachmentid, true );
				if($result){
					echo 'sucess: deleted attachment'.$attachmentid;
					echo "\r\n";
				}else{
					echo 'failure: deleted attachment'.$attachmentid;
					echo "\r\n";
				}
			}
		}
		*/
		return;
  	}
}

function somatic_attach_external_image( $url = null, $post_id = null, $filename = null, $post_data = array() ) {
	if ( !$url || !$post_id ) return new WP_Error('missing', "Need a valid URL and post ID...");
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	// Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
	$tmp = download_url( $url, 300 );

	// If error storing temporarily, unlink
	if ( is_wp_error( $tmp ) ) {
		@unlink($file_array['tmp_name']);   // clean up
		$file_array['tmp_name'] = '';
		return $tmp; // output wp_error
	}

	preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|pdf)/', $url, $matches);    // fix file filename for query strings

	if(empty($matches[0])){

		$headers = get_headers($url);
		$url_filename = $headers[4];

		//$url_type = wp_check_filetype($url_filename);
		$url_type['ext'] = 'pdf';

	}else{
		$url_filename = basename($matches[0]);                                                  // extract filename from url for title
		$url_type = wp_check_filetype($url_filename);
	}

	// determine file type (ext and mime/type)

	// override filename if given, reconstruct server path
	if ( !empty( $filename ) ) {
		$filename = sanitize_file_name($filename);
		$tmppath = pathinfo( $tmp );                                                        // extract path parts
		$new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];          // build new path
		rename($tmp, $new);                                                                 // renames temp file on server
		$tmp = $new;                                                                        // push new filename (in path) to be used in file array later
	}

	// assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
	$file_array['tmp_name'] = $tmp;                                                         // full server path to temp file

	if ( !empty( $filename ) ) {
		$file_array['name'] = $filename . "." . $url_type['ext'];                           // user given filename for title, add original URL extension
	} else {
		$file_array['name'] = $url_filename;                                                // just use original URL filename
	}

	// set additional wp_posts columns
	if ( empty( $post_data['post_title'] ) ) {
		$post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
	}

	// make sure gets tied to parent
	if ( empty( $post_data['post_parent'] ) ) {
		$post_data['post_parent'] = $post_id;
	}

	// required libraries for media_handle_sideload
	require_once('../wp-admin/includes/file.php');
	require_once('../wp-admin/includes/media.php');
	require_once('../wp-admin/includes/image.php');

	echo 'uploading image '.$file_array['name'];
	echo "\r\n";

	// do the validation and storage stuff
	$att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );             // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status

	echo 'uploaded image '.$file_array['name'];
	echo "\r\n";


	// If error storing permanently, unlink
	if ( is_wp_error($att_id) ) {
		@unlink($file_array['tmp_name']);   // clean up


		echo 'failure: media_handle_sideload ';
		echo "\r\n";

		print_r ($att_id);

		return $att_id; // output wp_error

	}else{
		echo 'sucess: image uploaded '.$att_id;
		echo "\r\n";
	}




	return $att_id;
}

function wp_get_attachment_by_post_name( $post_name ) {
	$args = array(
		'post_per_page' => 1,
		'post_type'     => 'attachment',
		'name'          => trim ( $post_name ),
		'suppress_filters' => false

	);
	$get_posts = new Wp_Query( $args );

	if ( $get_posts->posts[0] )
	return $get_posts->posts[0];
	else
	return false;
}

?>