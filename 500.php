<?php  

// Basic config
	include_once('inc/config.php');
	
// Override the HTTP status code
	header("HTTP/1.1 500 Internal Server Error");

// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/500.mustache'), 
					array('page_title' => 'Sorry there has been a problem (500)'), 
					$partials
					);
?>