<?php
	include('libraries/Mustache.php');
	include('libraries/tourcms.php');
	include('functions.php');

	// Base for page titles
	$page_titles_base = "";
	
	// Format for prettifying dates
	// http://php.net/manual/en/function.date.php
	$date_format = "l jS M Y";
	
	$marketplace_account_id = 0;
	$channel_id = 0;
	$api_private_key = "";
	
	$tourcms = new TourCMS($marketplace_account_id, $api_private_key, "simplexml");
	
	// Create a new Mustache instance
		$m = new Mustache();
		
	// Set up our partials
		$partials = array(
						"top" => file_get_contents($doc_root."/templates/top.mustache"),
						"bottom" => file_get_contents($doc_root."/templates/bottom.mustache"),
						"tours-single" => file_get_contents($doc_root."/templates/tours-single.mustache"),
						"nav" => file_get_contents($doc_root."/templates/nav.mustache")
		);
?>