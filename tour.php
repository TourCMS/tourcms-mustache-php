<?php
$tour_id = 1;

// Basic config
	$doc_root = $_SERVER['DOCUMENT_ROOT']."/scratch/api/tourcms-mustache-php";
	include($doc_root.'/inc/config.php');

// Create a new Mustache instance
$m = new Mustache();

// Set up our partials
$partials = array(
				"top" => file_get_contents($doc_root."/templates/top.mustache"),
				"bottom" => file_get_contents($doc_root."/templates/bottom.mustache"),
				"nav" => file_get_contents($doc_root."/templates/nav.mustache")
			);

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