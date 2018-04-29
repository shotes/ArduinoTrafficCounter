<?php
	//  chart_yearly.php
	// Aquires data and calculates statistics for yearly information
?>

<?php
	include 'configuration.php';
	//Return an associative array that holds all of the years, with them acting as keys that
	//reference the amount of people that triggered in the sensor for each year. 
	function accessData($start_year, $end_year){
 		$yearly_array = array();
		
		//Input Validation
		if(!ctype_digit($start_year) && !is_int($start_year)){
			return $yearly_array;
		}else if(!ctype_digit($end_year) && !is_int($end_year)){
			return $yearly_array;
		}
		
		//Connecting to database.
		$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
 		or die("Error Connecting.");
 		
 		//SQL request for 'date' column that is between two dates.
 		$sql = "SELECT `date` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-1-1' AND '". $end_year ."-12-31'";
 		
 		$result = mysqli_query($db, $sql);
 		
 		//Fills yaerly_array.
 		while($row = mysqli_fetch_assoc($result)){
 			//Explodes 'date' so that we can get 'year'.
 			$date_explode = explode('-', $row['date']);
 			$year = (int)$date_explode[0];
 			
 			//Initializing unique year arrays and filling them with values whenever a year has already been initialized.
 			if(!array_key_exists($year, $yearly_array)){
				$yearly_array[$year] = 1;
			}else{
				$yearly_array[$year] = $yearly_array[$year] + 1;
			}
 		}
 		
 		return $yearly_array;
	}
	
	//Get an associative array that holds all of the years, with them acting as keys that
	//reference the amount of people that triggered in the sensor for each year. 
	function getSeparateYearlyCounts($start_year, $end_year){
		$yearly_count = accessData($start_year, $end_year);
		return $yearly_count;
	}
	
	//Get the mean count that a year has had out of all the years in a timeframe.
	function getMeanYearlyCount($start_year, $end_year){
		$yearly_count = accessData($start_year, $end_year);
		$divideBy = count($yearly_count);
		$sum = 0;
		foreach($yearly_count as $key => $value){
			$sum += $value;
		}
		$average = $sum  /$divideBy;
		return (int)$average;
	}
	
	//Get the median count that a year has had out of all the years in a timeframe.
	function getMedianYear($start_year, $end_year){
		$holder_arr = accessData($start_year, $end_year);
		Arsort($holder_arr);
		$keys = array_keys($holder_arr);
		return $holder_arr[$keys[floor(count($keys)/2)]];
	}
	
	//Get the largest count that a year has had out of all the years in a timeframe.
	function getMaxYear($start_year, $end_year){
		$yearly_array = accessData($start_year, $end_year);
		return $yearly_array[array_search(max($yearly_array),$yearly_array)];
	}
	
	//Get the smallest count that a year has had out of all the years in a timeframe.
	function getMinYear($start_year, $end_year){
		$yearly_array = accessData($start_year, $end_year);
		return $yearly_array[array_search(min($yearly_array),$yearly_array)];
	}
	
?>