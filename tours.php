<?php
// Basic config
	include('inc/config.php');

// Include our Tour view
	include($doc_root.'/views/tours.php');
	$tours = new Tours($_GET);
	
// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/tours.mustache'), 
					$tours, 
					$partials
					);
?>