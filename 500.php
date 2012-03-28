<?php  

// Basic config
	include_once('inc/config.php');
	

	header("HTTP/1.1 500 Internal Server Error");
		
// Set up our partials
	$partials = array(
					"top" => file_get_contents("templates/top.mustache"),
					"bottom" => file_get_contents("templates/bottom.mustache"),
					"nav" => file_get_contents("templates/nav.mustache")
				);

	
// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/500.mustache'), 
					array('page_title' => 'Sorry there has been a problem (500)'), 
					$partials
					);
?>