<?php
	session_start();

	class Search {
	
		// Possible Search types (Tours or Hotels)
		public $poss_search_types = array("Accommodation", "Tours");
		public $search_type;
	
		// Months of the year
		protected $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		
		// Tour locations
		protected $poss_tour_locations = array("Any location", "Belgrade", "Novi Sad", "Multi-location tours");
		protected $poss_accom_locations = array("Any location", "Belgrade", "Novi Sad", "Multi-location tours");
		public $location;
		
		// Hotel locations
		protected $poss_accom_types = array("Any accommodation type", "Hotel", "Apartment");
		public $accom_type;
		
		// Holiday types (Categories)
		protected $poss_holiday_types = array("Any holiday type", "City Break", "Lakes and Mountains");
		public $holiday_type;
		
		// Tour types (Categories)
		protected $poss_tour_types = array("Any tour type", "Adventure", "Food and Drink", "Walking");
		protected $tour_type;
		
		// Start date
		public $start_day;
		public $start_month;
		public $start_year;
		
		// End date
		public $end_day;
		public $end_month;
		public $end_year;
		
		// Duration
		protected $min_duration;
		protected $max_duration;
		public $duration;
		
		protected $poss_flexibilities = array(3,5,7,10,0);
		protected $flexibility;
		
		function __construct() {
			// Set defaults;
			$this->search_type = "Tours";
		
			$this->start_day = (int)date("j");
			$this->start_month = date("M");
			$this->start_year = (int)date("Y");
		
			$this->end_day = (int)date("j");
			$this->end_month = date("M");
			$this->end_year = (int)date("Y");
			
			$this->min_duration = 1;
			$this->max_duration = 14;
			$this->duration = 7;
			
			$this->location = "Any location";
			$this->accom_type = "Any accommodation type";
			$this->holiday_type = "Any holiday type";
			$this->tour_type = "Any tour type";
			$this->transfer_from = "Travelling from";
			$this->transfer_to = "Travelling to";
			$this->journey_type = "Return journey";
			$this->vehicle_type = "Vehicle required";
			
			
			$this->flexibility = 3;
			
			// Either load the users specific settings or load them
			if(isset($_GET['search_type']))
				$this->populate($_GET);
			elseif(isset($_POST['search_type'])) 
				$this->populate($_POST);
			else
				$this->populate();
		}
		
		/*
			populate
			Populates form based on user searches
			Either querystring or cache
		*/
		public function populate($newdata = null) {
			
			/*
				Prep data to use when populating
			*/
				$data = null;
				
				// Load session data if it exists
				if(isset($_SESSION['searchdata']))
					$data = $_SESSION['searchdata'];
				
				// If new data exists load that over the top
				if(isset($newdata)) {
					foreach ($newdata as $key => $value) {
						$data[$key] = $value;
					}
				}
				
				// Save back to session
				$_SESSION['searchdata'] = $data;
			
			/*
				Validate and populate data
			*/
			
				// Search type
				if(isset($data["search_type"])) {
					if(in_array($data["search_type"], $this->poss_search_types))
						$this->search_type = $data["search_type"];
				}
			
				// Start date
				if(isset($data['start_day'])) {
					$this->start_day = (int)$data['start_day'];
				}
				if(isset($data['start_month'])) {
					$start_month = $data['start_month'];
					if(in_array($start_month, $this->months))
						$this->start_month = $start_month;
				}
				if(isset($data['start_year'])) {
					$this->start_year = (int)$data['start_year'];
				}
				
				$start_date = strtotime($this->start_month . "-" . $this->start_day . "-". $this->start_year);
				
				// Sanity check start date
				if($start_date < time())
					$start_date = time();

				$this->start_day = date("j", $start_date);
				$this->start_month = date("M", $start_date);
				$this->start_year = date("Y", $start_date);
				
				// End date
				if(isset($data['end_day'])) {
					$this->end_day = (int)$data['end_day'];
				}
				if(isset($data['end_month'])) {
					$end_month = $data['end_month'];
					if(in_array($end_month, $this->months))
						$this->end_month = $end_month;
				}
				if(isset($data['end_year'])) {
					$this->end_year = (int)$data['end_year'];
				}
				
				$end_date = strtotime($this->end_month . "-" . $this->end_day . "-". $this->end_year);
				
				// Sanity check end date
				if($end_date < $start_date) {
						$end_date = strtotime("+2 days", $start_date);
				} 
				
				$this->end_day = date("j", $end_date);
				$this->end_month = date("M", $end_date);
				$this->end_year = date("Y", $end_date);
				
				// Durations
				if(isset($data['duration'])) {
					$duration = $data['duration'];
					$duration = intval(preg_replace("/[^0-9]/", '', $duration));
					if($duration >= $this->min_duration && $duration <= $this->max_duration)
						$this->duration = $duration;
				}
				// Locations
				if($this->search_type=="Accommodation") {
					if(isset($data['location'])) {
						if(in_array($data["location"], $this->poss_accom_locations)) 
							$this->location = $data["location"];	
					}
				} else {
					if(isset($data['location'])) {
						if(in_array($data["location"], $this->poss_tour_locations)) 
							$this->location = $data["location"];	
					}
				}
				
				// Accom Type
				if(isset($data['accom_type'])) {
					if(in_array($data["accom_type"], $this->poss_accom_types)) 
						$this->accom_type = $data["accom_type"];	
				}
				
				// Holiday Type
				if(isset($data['holiday_type'])) {
					if(in_array($data["holiday_type"], $this->poss_holiday_types)) 
						$this->holiday_type = $data["holiday_type"];	
				}
				
				// Tour Type
				if(isset($data['tour_type'])) {
					if(in_array($data["tour_type"], $this->poss_tour_types)) 
						$this->tour_type = $data["tour_type"];	
				}
				
				// Flexibilities
				if(isset($data['flexibility'])) {
					$flexibility = $data['flexibility'];
					if($flexibility == "No flexibility")
						$flexibility = 0;
					$flexibility = intval(preg_replace("/[^0-9]/", '', $flexibility));
					if(in_array($flexibility, $this->poss_flexibilities))
						$this->flexibility = $flexibility;
				}
		}
		
		
		public function search_terms() {
			$search_terms = array();
			/*
				Populate search terms
				Different terms depending on search type
			*/
	
			switch ($this->search_type) {
				
			// Tour Search
				case "Tours":
					// Product Type
						$search_terms["product_type"] = "3,4,5,6,7,8";
					// Tour Type
						if($this->tour_type != "Any tour type")
							$search_terms["category"] = $this->tour_type;
					// Location
						if($this->location != "Any location")
							$search_terms["ANDcategory"] = "Location:".$this->location;
					// Start date
						$start_date = strtotime($this->start_month . "-" . $this->start_day . "-". $this->start_year);
						
						if($this->flexibility > 0) {
							$start_date_start = strtotime("-" . $this->flexibility . " days", $start_date);
							$start_date_end = strtotime("+" . $this->flexibility . " days", $start_date);
							$search_terms["start_date_start"] = date("Y-m-d", $start_date_start);
							$search_terms["start_date_end"] = date("Y-m-d", $start_date_end);
						} else {
							$search_terms["start_date"] = date("Y-m-d", $start_date);
						}
						
					break;
					
			// Accommodation Search
				default:
					// Product Type
						$search_terms["product_type"] = "1";
					
					// Categories
						// Accommodation Type
						if($this->accom_type != "Any accommodation type")
							$search_terms["category"] = $this->accom_type;
					
					$and_categories = array();
					
					// Location
						if($this->location != "Any location")
							$and_categories[] = "Location:".$this->location;
					
						// Type of Holiday
						
						if($this->holiday_type != "Any holiday type")
							$and_categories[] = $this->holiday_type;
					
					if(!empty($and_categories))
						$search_terms["ANDcategory"] = implode("|", $and_categories);
					
					// Start date
						$start_date = strtotime($this->start_month . "-" . $this->start_day . "-". $this->start_year);
						
						$search_terms["startdate_yyyymmdd"] = date("Y-m-d", $start_date);
						
					// Duration
						$search_terms["hdur"] = $this->duration;
			}
			
			return array($search_terms, $search_terms2);
			
		}
		
		
 // Start date helper functions
 // Used to draw datepickers in the main Holiday search box
 
 		// Start Day
		public function startdays() {
			$return = array();
			for($i=1; $i<=31; $i++) {
				$return[] = array(
							"day" => $i,
							"selected" => ($i==$this->start_day)
						);
			}
			return $return;
		}
		
		// Start Month
		public function startmonths() {
			$return = array();
			foreach($this->months as $month) {
				$return[] = array(
							"month" => $month,
							"selected" => ($month==$this->start_month)
						);
			}
			return $return;
		}
		
		// Start Year
		public function startyears() {
			$return = array();
			$thisyear = (int)date("Y");
			for($i=0; $i<=3; $i++) {
				$loopyear = $thisyear + $i;
				$return[] = array(
							"year" => $loopyear,
							"selected" => ($loopyear==$this->start_year)
						);
			}
			return $return;
		}
		
// End date helper functions
// Used to draw datepickers in the main Holiday search box
		
		// End Day
		public function enddays() {
			$return = array();
			for($i=1; $i<=31; $i++) {
				$return[] = array(
							"day" => $i,
							"selected" => ($i==$this->end_day)
						);
			}
			return $return;
		}
		
		// End Month
		public function endmonths() {
			$return = array();
			foreach($this->months as $month) {
				$return[] = array(
							"month" => $month,
							"selected" => ($month==$this->end_month)
						);
			}
			return $return;
		}
		
		// End Year
		public function endyears() {
			$return = array();
			$thisyear = (int)date("Y");
			for($i=0; $i<=3; $i++) {
				$loopyear = $thisyear + $i;
				$return[] = array(
							"year" => $loopyear,
							"selected" => ($loopyear==$this->end_year)
						);
			}
			return $return;
		}

// Other misc helper functions

		// Is Accommodation Search
		public function isaccomsearch() {
			return ($this->search_type=="Accommodation");
		}
		
		// Is Tour Search
		public function istoursearch() {
			return ($this->search_type=="Excursions");
		}
		
		// Is Transfer Search
		public function istransfersearch() {
			return ($this->search_type=="Transfers");
		}

		// Durations
		public function durations() {
			$return = array();
			for($i=$this->min_duration; $i<=$this->max_duration; $i++) {
				$return[] = array(
							"duration" => "For " . $i . " day" . ($i>1 ? "s" : null),
							"selected" => ($i==$this->duration)
						);
			}
			return $return;
		}
		
		public function nights() {
			$return = array();
			for($i=$this->min_duration; $i<=$this->max_duration; $i++) {
				$return[] = array(
							"duration" => "For " . $i . " night" . ($i>1 ? "s" : null),
							"selected" => ($i==$this->duration)
						);
			}
			return $return;
		}
		
		// Locations
		public function accom_locations() {
			$return = array();
			foreach($this->poss_accom_locations as $location) {
				$return[] = array(
							"location" => $location,
							"selected" => ($location==$this->location)
						);
			}
			return $return;
		}
		
		public function tour_locations() {
			$return = array();
			foreach($this->poss_tour_locations as $location) {
				$return[] = array(
							"location" => $location,
							"selected" => ($location==$this->location)
						);
			}
			return $return;
		}
		
		// Accommodation types
		public function accomtypes() {
			$return = array();
			foreach($this->poss_accom_types as $accom_type) {
				$return[] = array(
							"accomtype" => $accom_type,
							"selected" => ($accom_type==$this->accom_type)
						);
			}
			return $return;
		}
		
		// Holiday types
		public function holidaytypes() {
			$return = array();
			foreach($this->poss_holiday_types as $holiday_type) {
				$return[] = array(
							"holidaytype" => $holiday_type,
							"selected" => ($holiday_type==$this->holiday_type)
						);
			}
			return $return;
		}
		
		// Tour types
		public function tourtypes() {
			$return = array();
			foreach($this->poss_tour_types as $tour_type) {
				$return[] = array(
							"tourtype" => $tour_type,
							"selected" => ($tour_type==$this->tour_type)
						);
			}
			return $return;
		}
		
		// How flexible
		public function flexibilities() {
			$return = array();
			foreach($this->poss_flexibilities as $flexibility) {
			
				if($flexibility == 0)
					$flexibility_text = "No flexibility";
				else
					$flexibility_text = "Within $flexibility day" . ($flexibility > 1 ? "s" : null);
					
				$return[] = array(
							"flexibility" => $flexibility_text,
							"selected" => ($flexibility==$this->flexibility)
						);
			}
			return $return;
		}
		
		
	}
?>