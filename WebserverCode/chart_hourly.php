<?php
	//  chart_hourly.php
	// Aquires data and calculates statistics for hourly information
?>

<?php
	include 'configuration.php';
	//Gets all of the data and stores in an nested associative array ordered like this...
	//hourly_array[year][month][day][hour] = 'number of people that triggered sensor in this hour'
	function accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour){
		$hourly_array = array();
		
		//Input Validation
		if(!ctype_digit($start_year) && !is_int($start_year)){
			return $hourly_array;
		}else if(!ctype_digit($end_year) && !is_int($end_year)){
			return $hourly_array;
		}else if(!ctype_digit($start_month) && !is_int($start_month)){
			return $hourly_array;
		}else if(!ctype_digit($end_month) && !is_int($end_month)){
			return $hourly_array;
		}else if(!ctype_digit($start_day) && !is_int($start_day)){
			return $hourly_array;
		}else if(!ctype_digit($end_day) && !is_int($end_day)){
			return $hourly_array;
		}else if(!ctype_digit($start_hour) && !is_int($start_hour)){
			return $hourly_array;
		}else if(!ctype_digit($start_hour) && !is_int($start_hour)){
			return $hourly_array;
		}
		
		//Connecting to database.
		$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
 		or die("Error Connecting.");
 		
 		//SQL request for 'date' and 'time' column that is between two dates. 
 		$sql = "SELECT `date`, `time` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-". $start_day ."' AND '". $end_year ."-". $end_month ."-". $end_day ."'";
 		
 		$result = mysqli_query($db, $sql);
 		
 		//Building date values for restricting timeframe. 
 		$start_date = $start_year ."-". $start_month ."-". $start_day;
 		$end_date = $end_year ."-". $end_month ."-". $end_day;
 		
 		//Fills hourly_array.
 		while($row = mysqli_fetch_assoc($result)){
 			//Explodes 'date' and 'time' so that we can get 'year', 'month', 'date', and 'hour'.
 			$date_explode = explode('-', $row['date']);
 			$time_explode = explode(':', $row['time']);
 			$year = (int)$date_explode[0];
 			$month = (int)$date_explode[1]; 
 			$day = (int)$date_explode[2];
 			$hour = (int)$time_explode[0];

			//Setting format of time so that it runs on a 0-23 timeframe because of how 
			//the interface labels hours. 
 			if($start_hour == 12){
 				$start_hour = 0;
 			}else if($start_hour == 24){
 				$start_hour = 12;
 			}
 			
 			//Setting format of time so that it runs on a 0-23 timeframe because of how 
			//the interface labels hours. 
 			if($end_hour == 12){
 				$end_hour = 0;
 			}else if($end_hour == 24){
 				$end_hour = 12;
 			}
 			
 			//Skips all values for start date that are below the start time.
 			if($start_date == $row['date']){
 				if($hour < $start_hour)
 					continue;
 			}
 			
 			//Skips all values for enddate that are above the end time.
 			if($end_date == $row['date']){
 				if($hour > $end_hour)
 					continue;
 			}
 			
 			//Initializing unique year arrays. 
 			if(!array_key_exists($year, $hourly_array)){
				$hourly_array[$year] = array();
			}
			
			//Initializing unique month arrays.
			if(!array_key_exists($month, $hourly_array[$year])){
				$hourly_array[$year][$month] = array();
			}
			
			//Initializing unique day arrays.
			if(!array_key_exists($day, $hourly_array[$year][$month])){
				$hourly_array[$year][$month][$day] = array();
			}

			//Initializing unique hour arrays and filling them with values whenever an hour has already been initialized.
			if(!array_key_exists($hour, $hourly_array[$year][$month][$day])){
				$hourly_array[$year][$month][$day][$hour] = 1;
			}else{
				$hourly_array[$year][$month][$day][$hour] = $hourly_array[$year][$month][$day][$hour] + 1;
			}
 		}
 		
 		return $hourly_array;			
	}
		
	//Will return an associative array with the mean value for each hour.
	//For example, $mean_array[2] = 5 means that the mean amount of people that triggered the sensor between 2:00am and 2:59am is 5 people. 
	//It goes off of military time, so the hours will go from 0-23.	
	function getMeanHours($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour){
		//Declaring arrays	
		$HOURS_IN_DAY = 24;
		$hourly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour);
		$sum_array = array();
		$divideBy_array = array();
		$mean_array = array();
		
		//Initializing the arrays.
		//$sum_array will hold the number of people that have crossed in that specific hour, for the entire timeframe specified.
		//$dividBy_array will holder the number of unique hours that were pulled from for each hour (unique regarding year, month, day) 
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			$sum_array[$i] = 0;
			$divideBy_array[$i] = 0;
		}
		
		//This fills the previously described associative arrays, $sum_array and $divideBy_array.
		foreach($hourly_array as $year => $temp1){
			foreach($hourly_array[$year] as $month => $temp2){
				foreach($hourly_array[$year][$month] as $day => $temp3){
					foreach($hourly_array[$year][$month][$day] as $hour => $value){
						$sum_array[$hour] += $value;
						$divideBy_array[$hour] += 1;
					}
				}
			}
		}
		
		//This creates the associative array $mean_array dividing the $sum_array by the $divideBy_array, with the index
		//decided by the specific hour that has its mean being caluclated. 
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			$mean_array[$i] = (int)($sum_array[$i]/$divideBy_array[$i]);
		}
		
		//Any values that are still null will be set to 0.
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			if($mean_array[$i] == NULL)
				$mean_array[$i] = 0;
		}
			
 		return $mean_array;
	}
	
	//Will return an associative array with the median value for each hour.
	//For example, $median_array[2] = 5 means that the median amount of people that triggered the sensor between 2:00am and 2:59am is 5 people. 
	//It goes off of military time, so the hours will go from 0-23.
	function getMedianHours($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour){
		//Declaring arrays
		$HOURS_IN_DAY = 24;
		$hourly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour);
		$all_hours_array = array();
		$median_array = array();
		
		//Initializing the array that will hold every single count for each hour. 
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			$all_hours_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific hour stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, month, day, hour).
		foreach($hourly_array as $year => $temp1){
			foreach($hourly_array[$year] as $month => $temp2){
				foreach($hourly_array[$year][$month] as $day => $temp3){
					foreach($hourly_array[$year][$month][$day] as $hour => $value){
						array_push($all_hours_array[$hour], $value);
					}
				}
			}
		}
		
		//This will sort each array for each hour value in descending order, then it will set the median array value for that hour
		//by grabbing the middle value for each array from $all_hours_array.
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			sort($all_hours_array[$i]);
			$median_array[$i] = $all_hours_array[$i][floor(count($all_hours_array[$i])/2)];
		}
		
		//Any values that are still null will be set to 0.
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			if($median_array[$i] == NULL)
				$median_array[$i] = 0;
		}
		
		return $median_array;
	}
	
	//Will return an associative array with the largest value for each hour.
	//For example, $max_array[2] = 5 means that the largest amount of people that triggered the sensor between 2:00am and 2:59am is 5 people. 
	//It goes off of military time, so the hours will go from 0-23.
	function getMaxHours($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour){
		//Declaring arrays
		$HOURS_IN_DAY = 24;
		$hourly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour);
		$all_hours_array = array();
		$max_array = array();
		
		//Initializing the array that will hold every single count for each hour. 
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			$all_hours_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific hour stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, month, day, hour).
		foreach($hourly_array as $year => $temp1){
			foreach($hourly_array[$year] as $month => $temp2){
				foreach($hourly_array[$year][$month] as $day => $temp3){
					foreach($hourly_array[$year][$month][$day] as $hour => $value){
						array_push($all_hours_array[$hour], $value);
					}
				}
			}
		}
		
		//This will sort each array for each hour value in ascending order, then it will set the maximum array value for that hour
		//by grabbing the first value for each array from $all_hours_array.
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			rsort($all_hours_array[$i]);
			$max_array[$i] = $all_hours_array[$i][0];
		}
		
		//Any values that are still null will be set to 0.
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			if($max_array[$i] == NULL)
				$max_array[$i] = 0;
		}
		
		return $max_array;
	}
	
	//Will return an associative array with the smallest value for each hour.
	//For example, $min_array[2] = 5 means that the smallest amount of people that triggered the sensor between 2:00am and 2:59am is 5 people. 
	//It goes off of military time, so the hours will go from 0-23.
	function getMinHours($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour){
		//Declaring arrays
		$HOURS_IN_DAY = 24;
		$hourly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day, $start_hour, $end_hour);
		$all_hours_array = array();
		$min_array = array();
		
		//Initializing the array that will hold every single count for each hour. 
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			$all_hours_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific hour stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, month, day, hour).
		foreach($hourly_array as $year => $temp1){
			foreach($hourly_array[$year] as $month => $temp2){
				foreach($hourly_array[$year][$month] as $day => $temp3){
					foreach($hourly_array[$year][$month][$day] as $hour => $value){
						array_push($all_hours_array[$hour], $value);
					}
				}
			}
		}
		
		//This will sort each array for each hour value in descending order, then it will set the minimum array value for that hour
		//by grabbing the first value for each array from $all_hours_array.
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			sort($all_hours_array[$i]);
			$min_array[$i] = $all_hours_array[$i][0];
		}
		
		//Any values that are still null will be set to 0. 
		for($i = 0; $i < $HOURS_IN_DAY; $i++){
			if($min_array[$i] == NULL)
				$min_array[$i] = 0;
		}
		
		return $min_array;
	}
	
?>