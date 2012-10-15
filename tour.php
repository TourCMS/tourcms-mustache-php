<?php
// Basic config
	include('inc/config.php');

// Process querystring
	$tour_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
	$channel_id = isset($_GET['channel_id']) ? (int)$_GET['channel_id'] : $channel_id;

// If we don't have a Tour ID we need to redirect to an error page
if(!$tour_id) {
	include($doc_root.'/403.php');
	exit();
}

// Set up Tour view
	include($doc_root.'/views/tour.php');
	$tour = new Tour($tour_id, $channel_id);
	
// Redirect if an incorrect URL has been used to call the page
 /*
	if($tour->url!=$_SERVER['REQUEST_URI']) {
		header("Location: " . $tour->url);
	}*/

// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/tour.mustache'), 
					$tour, 
					$partials
					);

?>