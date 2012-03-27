<?php
/**
 * Tour view
 *
 * Wrapper for the "Show Tour/Hotel" API call
 * Returns all API accessible information about a particular Tour/Hotel
 * http://www.tourcms.com/support/api/mp/tour_show.php
 *
 */
 
	class Tour {
	
		/**
		 * Constructor
		 *
		 * @param int    $id  TourCMS ID number for the Tour/Hotel to return.
		 * @param int	 $channel TourCMS ID number for the Channel the Tour/Hotel belongs to
		 */
		function __construct($id, $channel_id = 0) {
			global $page_titles_base;
			global $tourcms;
			if($channel_id == 0) 
				global $channel_id;
			
			$result = $tourcms->show_tour($id, $channel_id);
			$this->error = (string)$result->error;
			
			if($this->error=="OK") {
			
				foreach ($result->tour->children() as $element => $value) { 
					$this->$element = $value;
				}
				
			}
			
			$this->page_title = $this->tour_name;
			
			// Generate pretty URL
			if($this->product_type==1)
				$temp = "accommodation";
			elseif($this->product_type==2) 
				$temp = "transfer";
			else
				$temp = "tour";
			$this->url = get_product_url($this->tour_name, $this->tour_id, $temp);
			
			// Store a counter in the images
			$temp = 1;
			foreach ($this->images->image as $image) {
				$image->counter = $temp;
				$temp++;
			}
				
			// Process each rate
			// Used if building own API based booking engine or shopping cart
			// Not required if handing off to standard TourCMS booking engine
			foreach ($this->new_booking->people_selection->rate as $rate) {
				
				$temp_label = "";
				
				// Process the labels, or set a default
				(string)$rate->label_1 != "" ? $temp_label = $rate->label_1 : $temp_label = "Number of people";
				(string)$rate->label_2 != "" ? $temp_label .= " (" . $rate->label_2 . ")" : null;
				
				$rate->label = htmlspecialchars($temp_label);
				
				// Process the min/max quantities
				$min = (int)$rate->minimum;
				$max = (int)$rate->maximum;
				
				for($i=$min; $i<=$max; $i++) 
					$rate->addChild("capacities", $i);
					
				
			}
			
			// Process custom data
			if(isset($result->custom_fields->field[0])) {
				foreach ($result->custom_fields->field as $custom_field) {
					if((string)$custom_field->name=="bullet4")
						$this->bullet4 = (string)$custom_field->value;
					elseif((string)$custom_field->name=="starrating")
							$this->starrating = (string)$custom_field->value." stars";
					elseif((string)$custom_field->name=="image") {
						$this->extra_image = (string)$custom_field->value;
						if($this->extra_image!="") {
							$dom = new DOMDocument();
							$dom->loadHTML($this->extra_image);
							$this->extra_image = $dom->getElementsByTagName('img')->item(0)->getAttribute('src');
						}
					}
				}
			}
				
		}
	
	/*
		nl2br'd content helpers 
	*/
		/**
		 * Returns the Tour/Hotel longdesc passed through PHP's nl2br function
		 * http://php.net/manual/en/function.nl2br.php
		 *
		 */
		public function nl2brLongdesc() {
			if(isset($this->longdesc))
				return nl2br($this->longdesc);
			else 
				return "";
		}
		
		/**
		 * Returns the Tour/Hotel itinerary passed through PHP's nl2br function
		 * http://php.net/manual/en/function.nl2br.php
		 *
		 */
		public function nl2britinerary() {
			if(isset($this->itinerary))
				return nl2br($this->itinerary);
			else 
				return "";
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
		
		public function urlisok() {
			return ($this->tour_url == "??".$this->url);
		}
		
		public function urlisnotok() {
			return !$this->urlisok();
		}
	
	/*
		New booking helpers
	*/
		/**
		 * Whether a duration should be supplied when making checking availability
		 * Checks whether the Tour/Hotel is of a product type where a duration should be supplied when making checking availability (generally only used when writing own API based booking engine or shopping cart)
		 *
		 * @return boolean True if a duration should be specified
		 */
		public function needhdur() {
			$date_type = (string)$this->new_booking->date_selection->date_type;
			
			return $date_type == "DATE_NIGHTS" || "DATE_DAYS";
		}
	
		/**
		 * Whether a number of nights should be supplied when making checking availability
		 * Checks whether the Tour/Hotel is of a product type where a duration in number of nights should be supplied when making checking availability (generally only used when writing own API based booking engine or shopping cart)
		 *
		 * @return boolean True if a number of nights should be specified
		 */
		public function isnights() {
			return ((string)$this->new_booking->date_selection->date_type == "DATE_NIGHTS");
		}
		
		/**
		 * Checks whether a number of days should be supplied when making checking availability
		 * Checks whether the Tour/Hotel is of a product type where a duration in number of days should be supplied when making checking availability (generally only used when writing own API based booking engine or shopping cart)
		 *
		 * @return boolean True if a number of dAys should be specified
		 */
		public function isdays() {
			return ((string)$this->new_booking->date_selection->date_type == "DATE_DAYS");
		}
		
		/**
		 * Returns list of possible durations
		 *
		 * @return array Array containing possible durations
		 */
		public function durations() {
			if($this->needhdur()) {
				$min = (int)$this->new_booking->date_selection->duration_minimum;
				$max = (int)$this->new_booking->date_selection->duration_maximum;
				
				$durations = array();
				for($i=$min; $i<=$max; $i++) {
			
						$durations[] = array(
											"selected" => ($i==7),
											"value" => $i
											);

				}
				
				return $durations;
			} else {
				return false;
			}
		}
	
	/* 
		Product type helpers
	*/
	
		/**
		 * Checks whether the product is of type 1 (Hotel)
		 *
		 * @return boolean True if hotel
		 */
		public function ishotel() {
			return ((int)$this->product_type == 1);
		}
		
		/**
		 * Checks whether the product is NOT of type 1 (Hotel)
		 *
		 * @return boolean True if NOT a hotel
		 */
		public function nothotel() {
			return !$this->ishotel();
		}
	
	/*
		Misc helpers
	*/	
		
		

	}
?>