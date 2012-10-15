<?php
	// Function for prettifying dates
	function prettify_date($date) {
		global $date_format;
		
		return date($date_format, strtotime($date));
	}
	
	// Get a product url
	function get_product_url ($name, $id, $type = "tour") {
		if($type=="transfer")
			return "/transfers";
		if($type!="accommodation")
			$type .= "s";
		return "/".$type."/".nofunnychar_name_url($name)."_".$id."/";
	}
	
	
	function nofunnychar_name_url( $name )
	{
				//$name = mb_convert_encoding( $name, 'ISO-8859-1', 'UTF-8' );
	
				$table = array(
				        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
				        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
				        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
				        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
				        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
				        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
				        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'á' => 'a',
				    );
				    
				   // $name = utf8_decode($name);
				// assumes has already been stripslashed
				// Need to swap spaces for _ in the $name	
				$name = trim( $name );
				$name = str_replace( "/", "-", $name );
				$name = str_replace( "\\", "-", $name );
				$name = str_replace( ":", "-", $name );
				$name = str_replace( ";", "-", $name );
				$name = str_replace( "@", "-", $name );
				$name = str_replace( ">", "-", $name );
				$name = str_replace( "<", "-", $name );
				$name = str_replace( ".", "-", $name );
				$name = str_replace( ",", "-", $name );
				$name = str_replace( "(", "-", $name );
				$name = str_replace( ")", "-", $name );
				$name = str_replace( "|", "-", $name );
				$name = str_replace( "'", "-", $name );
				$name = str_replace( '"', "-", $name );
				$name = str_replace( '?', "-", $name );
				$name = str_replace( '&', "-", $name );
				$name = str_replace( '_', "-", $name );
				$name = str_replace( '~', "-", $name );
				$name = str_replace( '#', "-", $name );
				$name = str_replace( " ", "-", $name );
				$name = str_replace( "---", "-", $name );
				$name = str_replace( "--", "-", $name );
				$name = str_replace( "*", "", $name );
				//$name = str_replace( "„", "", $name );
				//$name = str_replace( "“", "", $name );
				//$name = utf8_decode($name);
				$name = strtr($name, utf8_decode("řäåöáøë"), "raaoaoe");
				//print $name;
				 
				
				return mb_strtolower( $name );
	}
?>