<?php
/**
 * Tour view
 *
 * Wrapper for the "Show Tour/Hotel Dates and Deals" API call
 * Returns all availability information on a particular Tour/Hotel
 * http://www.tourcms.com/support/api/mp/tour_datesdeals_show.php
 *
 */
 
	class Deals {
		/**
		 * Store the API data retrieved
		 *
		 */
		public $dates_and_prices;
		
		/**
		 * Constructor
		 *
		 * @param int    $id  TourCMS ID number for the Tour/Hotel to return.
		 * @param int	 $channel TourCMS ID number for the Channel the Tour/Hotel belongs to
		 */
		function __construct($id, $params, $channel_id = 0) {
			global $date_format;
			global $page_titles_base;
			global $tourcms;
			if($channel_id == 0) 
				global $channel_id;
			
			$result = $tourcms->show_tour_datesanddeals($id, $channel_id);
			$this->error = (string)$result->error;
			
			if($this->error=="OK") {
				foreach($result->dates_and_prices->date as $date) {
					$this->dates_and_prices[] = array(
									"start_date" => (string)$date->start_date,
									  "end_date" => (string)$date->end_date,
							"start_date_display" => date($date_format, strtotime($date->start_date)),
							  "end_date_display" => date($date_format, strtotime($date->end_date)),
							  		  "book_url" => (string)$date->book_url,
							  	 "sale_currency" => (string)$date->sale_currency,
							  		   "price_1" => (string)$date->price_1,
				 			   "price_1_display" => (string)$date->price_1_display,
							  		   "price_2" => (string)$date->price_2,
							   "price_2_display" => (string)$date->price_2_display,
							   "cheapest_price" => ((float)$date->price_2 / 2) > $date->price_1 ? $date->price_1 : (float)$date->price_2 / 2
					);
				}
			}
		}
		
		/*
			Status helpers
		*/					
			
			/**
			 * Checks if the Tour/Hotel was loaded from the API ok
			 *
			 * @return boolean True if the product was loaded ok
			 */
			public function isok() {
				return (string)$this->error==="OK";
			}
			
			/**
			 * Checks if the Tour/Hotel was NOT loaded from the API ok
			 *
			 * @return boolean True if there was a problem loading the product
			 */
			public function notok() {
				return !$this->isok();
			}
			
	}
?>