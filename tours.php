<?php
	// Basic config
		$doc_root = $_SERVER['DOCUMENT_ROOT']."/scratch/api/tourcms-mustache-php";
	include($doc_root.'/inc/config.php');

	$m = new Mustache();
	
	// Set up our partials
	$partials = array(
					"top" => file_get_contents($doc_root."/templates/top.mustache"),
					"bottom" => file_get_contents($doc_root."/templates/bottom.mustache"),
					"tours-single" => file_get_contents($doc_root."/templates/tours-single.mustache"),
					"nav" => file_get_contents($doc_root."/templates/nav.mustache")
	);

	// Include our Tour view
	include($doc_root.'/views/tours.php');
	
	// Instantiate and populate it
	$tours = new Tours();
	
	// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/tours.mustache'), 
					$tours, 
					$partials
					);
?>