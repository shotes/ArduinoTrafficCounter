<?php
	//  chart_weekly.php
	// Aquires data and calculates statistics for weekly information
?>

<?php
	include 'configuration.php';
	//Gets all of the data and stores in an nested associative array ordered like this...
	//weekly_array[year][week] = 'number of people that triggered sensor in this week'
	function accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		$weekly_array = array();
		
		//Input Validation
		if(!ctype_digit($start_year) && !is_int($start_year)){
			return $daily_array;
		}else if(!ctype_digit($end_year) && !is_int($end_year)){
			return $daily_array;
		}else if(!ctype_digit($start_month) && !is_int($start_month)){
			return $daily_array;
		}else if(!ctype_digit($end_month) && !is_int($end_month)){
			return $daily_array;
		}else if(!ctype_digit($start_day) && !is_int($start_day)){
			return $daily_array;
		}else if(!ctype_digit($end_day) && !is_int($end_day)){
			return $daily_array;
		}
		
		//Connecting to database.
		$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
 		or die("Error Connecting.");
 		
 		//SQL request for 'date' column that is between two dates. 
 		$sql = "SELECT `date` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-". $start_day ."' AND '". $end_year ."-". $end_month ."-". $end_day ."'";
 		
 		
 		$result = mysqli_query($db, $sql);
 		
 		//Fills weekly_array.
 		while($row = mysqli_fetch_assoc($result)){
 			//Explodes 'date' so that we can get 'year', 'month', and 'day'.
 			$date_explode = explode('-', $row['date']);
 			$year = (int)$date_explode[0];
 			
 			//Getting the week integer value from the given date value. (1-52)
 			$getting_week_date = mktime(0, 0, 0, $date_explode[1], $date_explode[2], $date_explode[0]);
 			$week = (int)date('W', $getting_week_date);
 			
 			//Initializing unique year arrays. 
 			if(!array_key_exists($year, $weekly_array)){
				$weekly_array[$year] = array();
			}

			//Initializing unique week arrays and filling them with values whenever a week has already been initialized.
			if(!array_key_exists($week, $weekly_array[$year])){
				$weekly_array[$year][$week] = 1;
			}else{
				$weekly_array[$year][$week] = $weekly_array[$year][$week] + 1;
			}
 		}
 		
 		return $weekly_array;		
	}
	
	//Will return the number of people that triggered the sensor for a week as a mean count,
	//out of all weeks in a specified timeframe.	
	function getOverallWeekMean($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$divideBy = 0;
		$sum = 0;
		foreach($weekly_array as $key => $key2){
			foreach($weekly_array[$key] as $key3 => $value){
				$sum += $value;
				$divideBy++;
			}
		}
		$average = $sum  /$divideBy;
		return (int)$average;
	}
	
	//Will return the number of people that triggered the sensor for the week with the median count,
	//out of all weeks in a specified timeframe.	
	function getOverallMedianWeek($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$median_array = array();
		
		//Pushing all week values to an associative array.
		foreach($weekly_array as $key => $key2){
			foreach($weekly_array[$key] as $key3 => $value){
				array_push($median_array, $value);
			}
		}
		
		//Sorting the array in descending order.
		sort($median_array);	
	
		return $median_array[floor(count($median_array)/2)];
	}
		
	//Will return the number of people that triggered the sensor for the week with the largest count,
	//out of all weeks in a specified timeframe.	
	function getOverallMaxWeek($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$max_array = array();
	
		//Pushing all week values to an associative array. 
		foreach($weekly_array as $key => $key2){
			foreach($weekly_array[$key] as $key3 => $value){
				array_push($max_array, $value);
			}
		}
		
		//Sorting the array in ascending order.
		rsort($max_array);
				
		return $max_array[0];
	}
	
	//Will return the number of people that triggered the sensor for the week with the smallest count,
	//out of all weeks in a specified timeframe. 
	function getOverallMinWeek($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$min_array = array();
		
		//Pushing all week values to an associative array. 
		foreach($weekly_array as $key => $key2){
			foreach($weekly_array[$key] as $key3 => $value){
				array_push($min_array, $value);
			}
		}
		
		//Sorting the array in descending order. 
		sort($min_array);	
			
		return $min_array[0];
	}
	
	//Will return an associative array with the mean value for each week.
	//For example, $mean_array[2] = 5 means that the mean amount of people that triggered the sensor on week 2 is 5 people. 
	//The week values go from 1-52.	
	function getMeanWeeks($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		//Declaring arrays	
		$WEEKS_IN_YEAR = 53;
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$sum_array = array();
		$divideBy_array = array();
		$mean_array = array();
		
		//Initializing the arrays.
		//$sum_array will hold the number of people that have crossed in that specific week, for the entire timeframe specified.
		//$dividBy_array will holder the number of unique weeks that were pulled from for each week (unique regarding year) 
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			$sum_array[$i] = 0;
			$divideBy_array[$i] = 0;
		}
		
		//This fills the previously described associative arrays, $sum_array and $divideBy_array.
		foreach($weekly_array as $year => $temp1){
			foreach($weekly_array[$year] as $week => $value){
				$sum_array[$week] += $value;
				$divideBy_array[$week] += 1;
			}
		}
		
		//This creates the associative array $mean_array dividing the $sum_array by the $divideBy_array, with the index
		//decided by the specific week that has its mean being caluclated. 
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			$mean_array[$i] = (int)($sum_array[$i]/$divideBy_array[$i]);
		}
		
		//Any values that are still null will be set to 0.
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			if($mean_array[$i] == NULL)
				$mean_array[$i] = 0;
		}
			
 		return $mean_array;
	}
	
	//Will return an associative array with the median value for each week.
	//For example, $median_array[2] = 5 means that the median amount of people that triggered the sensor on week 2 is 5 people. 
	//The week values go from 1-52.
	function getMedianWeeks($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		//Declaring arrays
		$WEEKS_IN_YEAR = 53;
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$all_weeks_array = array();
		$median_array = array();
	
		//Initializing the array that will hold every single count for each week. 
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			$all_weeks_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific week stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, week).
		foreach($weekly_array as $year => $temp1){
			foreach($weekly_array[$year] as $week => $value){
				array_push($all_weeks_array[$week], $value);
			}
		}
		
		//This will sort each array for each week value in descending order, then it will set the median array value for that week
		//by grabbing the middle value for each array from $all_weeks_array.
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			sort($all_weeks_array[$i]);
			$median_array[$i] = $all_weeks_array[$i][floor(count($all_weeks_array[$i])/2)];
		}
		
		//Any values that are still null will be set to 0.
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			if($median_array[$i] == NULL)
				$median_array[$i] = 0;
		}
		return $median_array;
	}
	
	//Will return an associative array with the largest value for each week.
	//For example, $max_array[2] = 5 means that the largest amount of people that triggered the sensor on week 2 is 5 people. 
	//The week values go from 1-52.
	function getMaxWeeks($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		//Declaring arrays
		$WEEKS_IN_YEAR = 53;
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$all_weeks_array = array();
		$max_array = array();
		
		//Initializing the array that will hold every single count for each week. 
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			$all_weeks_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific week stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year,week).
		foreach($weekly_array as $year => $temp1){
			foreach($weekly_array[$year] as $week => $value){
				array_push($all_weeks_array[$week], $value);
			}
		}
		
		//This will sort each array for each week value in ascending order, then it will set the maximum array value for that week
		//by grabbing the first value for each array from $all_weeks_array.
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			rsort($all_weeks_array[$i]);
			$max_array[$i] = $all_weeks_array[$i][0];
		}
		
		//Any values that are still null will be set to 0.
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			if($max_array[$i] == NULL)
				$max_array[$i] = 0;
		}
		
		return $max_array;
	}
	
	//Will return an associative array with the smallest value for each week.
	//For example, $min_array[2] = 5 means that the smallest amount of people that triggered the sensor on week 2 is 5 people. 
	//The week values go from 1-52.
	function getMinWeeks($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		//Declaring arrays
		$WEEKS_IN_YEAR = 53;
		$weekly_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$all_weeks_array = array();
		$min_array = array();
	
		//Initializing the array that will hold every single count for each week. 
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			$all_weeks_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific week stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, week).
		foreach($weekly_array as $year => $temp1){
			foreach($weekly_array[$year] as $week => $value){
				array_push($all_weeks_array[$week], $value);
			}
		}
		
		//This will sort each array for each week value in descending order, then it will set the minimum array value for that week
		//by grabbing the first value for each array from $all_weeks_array.
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			sort($all_weeks_array[$i]);
			$min_array[$i] = $all_weeks_array[$i][0];
		}
		
		//Any values that are still null will be set to 0. 
		for($i = 1; $i < $WEEKS_IN_YEAR; $i++){
			if($min_array[$i] == NULL)
				$min_array[$i] = 0;
		}
		
		return $min_array;
	}
	
?>