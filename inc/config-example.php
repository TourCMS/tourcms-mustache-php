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
?>