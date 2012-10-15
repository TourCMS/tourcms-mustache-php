<?php
// Basic config
	include('inc/config.php');

// Process querystring
	$channel_id = isset($_GET['channel_id']) ? (int)$_GET['channel_id'] : $channel_id;

// Include our Tour view
	include($doc_root.'/views/tours.php');
	$tours = new Tours($_GET);
	
// Add a new partial
	$partials["tours-single"] = file_get_contents($doc_root."/templates/tours-single.mustache");
	
// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/tours.mustache'), 
					$tours, 
					$partials
					);
?>