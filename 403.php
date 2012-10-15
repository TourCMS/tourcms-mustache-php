<?php
	
// Basic config
	include_once('inc/config.php');
	
// Override the HTTP status code	
	header("HTTP/1.1 403 FORBIDDEN");
	
// Process our templates
	echo $m->render(
				file_get_contents($doc_root.'/templates/403.mustache'), 
				array('page_title' => 'Sorry there has been a problem (403)'), 
				$partials
	);
?>