<?php
	
// Basic config
	include_once('inc/config.php');
	
// Override the HTTP status code	
	header("HTTP/1.1 404 Not Found");
	
// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/404.mustache'), 
					array('page_title' => 'Page not found (404)'), 
					$partials
					);
?>