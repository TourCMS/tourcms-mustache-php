<?php
// Basic config
	include('inc/config.php');

// Set up Tour view
	include($doc_root.'/views/search.php');
	$search = new Search();

// Process our templates
	echo $m->render(
					file_get_contents($doc_root.'/templates/search-box.mustache'), 
					$search, 
					$partials
					);
?>