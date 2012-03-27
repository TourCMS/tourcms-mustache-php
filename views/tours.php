<?php
/**
 * Tours view
 *
 * Wrapper for the "Search Tours/Hotels" API call
 * Provides basic information on Tours/Hotels matching search criteria
 * http://www.tourcms.com/support/api/mp/tour_search.php
 *
 */
 
	class Tours {
		public $tours = array();
	
		function __construct($params = array()) {
			global $page_titles_base;
			global $tourcms;
			global $channel_id;
			
			$qs = "";
			
			if(!empty($params)) {
				$qs = http_build_query($params);
			}
			
			$qs .= "&per_page=200";			
			
			$tours = array();
			
			$result = $tourcms->search_tours($qs, $channel_id);
			$this->error = (string)$result->error;
					
			if($this->error=="OK") {
				foreach ($result->tour as $tour) { 
					$this->tours[] = $tour;
				}
			}
			
			$this->page_title = "Search results".$page_titles_base;
			
			// Generate pretty URLs
			if(isset($this->tours)) {
				foreach($this->tours as $tour) {
					$tour->url = get_product_url($tour->tour_name, $tour->tour_id, $tour->product_type == 1 ? "accommodation" : "tour");
				}
			}
			
		}
		
		public function isok() {
			return (string)$this->error==="OK";
		}
		
		public function notok() {
			return !$this->isok();
		}
		
		

	}
?>