<?php
	//  chart_daily.php
	// Aquires data and calculates statistics for daily information
?>


<?php
	include 'configuration.php';
	// $date is 'yyyy-mm-dd'
	// When given a date, will return the weekday in an integer with 0-6 corresponding to Sun-Sat respectively. 
	function getWeekDay($date) {
    	return date('w', strtotime($date));
	}
	
	// Gets all of the data and stores in an nested associative array ordered like this...
	// hourly_array[year][month][day] = 'number of people that triggered sensor in this day'
	function accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		 $daily_array = array();

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
		
		// Connecting to database.
		$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
 		or die("Error Connecting.");
 		
 		// SQL request for 'date' column that is between two dates. 
 		$sql = "SELECT `date` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-". $start_day ."' AND '". $end_year ."-". $end_month ."-". $end_day ."'";
 		
 		$result = mysqli_query($db, $sql);
 		
 		// Fills daily_array.
 		while($row = mysqli_fetch_assoc($result)) {
 			// Explodes 'date' so that we can get 'year', 'month', and 'day'.
 			$date_explode = explode('-', $row['date']);
 			$year = (int)$date_explode[0];
 			$month = (int)$date_explode[1]; 
 			$day = (int)$date_explode[2];
 			
 			// Initializing unique year arrays.
 			if(!array_key_exists($year, $daily_array)){
				$daily_array[$year] = array();
			}
			
			// Initializing unique month arrays.
			if(!array_key_exists($month, $daily_array[$year])){
				$daily_array[$year][$month] = array();
			}
			
			// Initializing unique day arrays and filling them with values whenever an day has already been initialized.
			if(!array_key_exists($day, $daily_array[$year][$month]))
			{
				$daily_array[$year][$month][$day] = 1;
			}
			else
			{
				$daily_array[$year][$month][$day] = $daily_array[$year][$month][$day] + 1;
			}
 		}
 		
 		return $daily_array;			
	}
	
	// Will return an associative array with the mean value for each day.
	// For example, $mean_array[2] = 5 means that the mean amount of people that triggered the sensor on Tuesday is 5 people. 
	// It goes off of weekdays, so Sun-Sat.		
	function getMeanDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		// Declaring arrays
		$DAYS_IN_WEEK = 7;
		$daily_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$sum_array = array();
		$divideBy_array = array();
		$mean_array = array();
		
		// Initializing the arrays.
		// $sum_array will hold the number of people that have crossed on that specific day, for the entire timeframe specified.
		// $dividBy_array will holder the number of unique days that were pulled from for each weekday (unique regarding year, month)
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			$sum_array[$i] = 0;
			$divideBy_array[$i] = 0;
		}
		
		// This fills the previously described associative arrays, $sum_array and $divideBy_array.
		// The index is created with the getWeekDay function so that it goes from 0-6.
		foreach($daily_array as $year => $temp1){
			foreach($daily_array[$year] as $month => $temp2){
				foreach($daily_array[$year][$month] as $day => $value){
					$weekday_index = getWeekDay($year ."-". $month ."-" .$day);
					$sum_array[$weekday_index] += $value;
 					$divideBy_array[$weekday_index] += 1;
				}
			}
		}
		
		// This creates the associative array $mean_array dividing the $sum_array by the $divideBy_array, with the index
		// decided by the specific weekday that has its mean being caluclated. 
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			$mean_array[$i] = (int)($sum_array[$i]/$divideBy_array[$i]);
		}
		
		// Any values that are still null will be set to 0.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			if($mean_array[$i] == NULL)
				$mean_array[$i] = 0;
		}
			
 		return $mean_array;
	}
	
	// Will return an associative array with the median value for each day.
	// For example, $median_array[2] = 5 means that the median amount of people that triggered the sensor on Tuesday is 5 people. 
	// It goes off of weekdays, so Sun-Sat.
	function getMedianDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		// Declaring arrays
		$DAYS_IN_WEEK = 7;
		$daily_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$all_days_array = array();
		$median_array = array();
		
		// Initializing the array that will hold every single count for each weekday. 
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			$all_days_array[$i] = array();
		}
		
		// This creates an associative array, with all the results for a specific weekday stored in an array contained within the associative array.
		// A value indicates how many people triggered the sensor for that specific time period (year, month, day).
		foreach($daily_array as $year => $temp1){
			foreach($daily_array[$year] as $month => $temp2){
				foreach($daily_array[$year][$month] as $day => $value){
					$weekday_index = getWeekDay($year ."-". $month ."-" .$day);
					array_push($all_days_array[$weekday_index], $value);
				}
			}
		}
		
		// This will sort each array for each weekday value in descending order, then it will set the median array value for that hour
		// by grabbing the middle value for each array from $all_days_array.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			sort($all_days_array[$i]);
			$median_array[$i] = $all_days_array[$i][floor(count($all_days_array[$i])/2)];
		}
		
		// Any values that are still null will be set to 0.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			if($median_array[$i] == NULL)
				$median_array[$i] = 0;
		}
		
		return $median_array;
	}
	
	// Will return an associative array with the largest value for each weekday.
	// For example, $max_array[2] = 5 means that the largest amount of people that triggered the sensor on Tuesday is 5 people. 
	// It goes off of weekdays, so Sun-Sat.
	function getMaxDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		// Declaring arrays
		$DAYS_IN_WEEK = 7;
		$daily_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$all_days_array = array();
		$max_array = array();
	
		// Initializing the array that will hold every single count for each weekday. 
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			$all_days_array[$i] = array();
		}
		
		// This creates an associative array, with all the results for a specific weekday stored in an array contained within the associative array.
		// A value indicates how many people triggered the sensor for that specific time period (year, month, day).
		foreach($daily_array as $year => $temp1){
			foreach($daily_array[$year] as $month => $temp2){
				foreach($daily_array[$year][$month] as $day => $value){
					$weekday_index = getWeekDay($year ."-". $month ."-" .$day);
					array_push($all_days_array[$weekday_index], $value);
				}
			}
		}
		
		// This will sort each array for each weekday value in ascending order, then it will set the maximum array value for that weekday
		// by grabbing the first value for each array from $all_days_array.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			rsort($all_days_array[$i]);
			$max_array[$i] = $all_days_array[$i][0];
		}
		
		// Any values that are still null will be set to 0.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			if($max_array[$i] == NULL)
				$max_array[$i] = 0;
		}
		
		return $max_array;
	}
	
	// Will return an associative array with the smallest value for each weekday.
	// For example, $min_array[2] = 5 means that the smallest amount of people that triggered the sensor on Tuesday is 5 people. 
	// It goes off of weekdays, so Sun-Sat.
	function getMinDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day){
		// Declaring arrays
		$DAYS_IN_WEEK = 7;
		$daily_array = accessData($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		$all_days_array = array();
		$min_array = array();
		
		// Initializing the array that will hold every single count for each weekday. 
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			$all_days_array[$i] = array();
		}
		
		// This creates an associative array, with all the results for a specific weekday stored in an array contained within the associative array.
		// A value indicates how many people triggered the sensor for that specific time period (year, month, day).
		foreach($daily_array as $year => $temp1){
			foreach($daily_array[$year] as $month => $temp2){
				foreach($daily_array[$year][$month] as $day => $value){
					$weekday_index = getWeekDay($year ."-". $month ."-" .$day);
					array_push($all_days_array[$weekday_index], $value);
				}
			}
		}
		
		// This will sort each array for each weekday value in descending order, then it will set the minimum array value for that weekday
		// by grabbing the first value for each array from $all_days_array.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			sort($all_days_array[$i]);
			$min_array[$i] = $all_days_array[$i][0];
		}
		
		// Any values that are still null will be set to 0.
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			if($min_array[$i] == NULL)
				$min_array[$i] = 0;
		}
		
		return $min_array;
	}
	
	// Attaches names to the associative array keys, so rather than having arr[0] = 2, you'll have arr[Sunday] = 2.
	function attachDaysNames($requestedStat, $start_year, $end_year, $start_month, $end_month, $start_day, $end_day)
	{
		$DAYS_IN_WEEK = 7;
		// Deciding which functions to call to get the array that needs to have its keys adjusted. 
		if($requestedStat == "mean"){
			$temp_array = getMeanDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		}
		else if($requestedStat == "median"){
			$temp_array = getMedianDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		}
		else if($requestedStat == "max"){
			$temp_array = getMaxDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		}
		else if($requestedStat == "min"){
			$temp_array = getMinDays($start_year, $end_year, $start_month, $end_month, $start_day, $end_day);
		};
		
		// Replacing integers with their string weekday names. 
		$day_names = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
		$named_array = array();
		for($i = 0; $i < $DAYS_IN_WEEK; $i++){
			$named_array[$day_names[$i]] = $temp_array[$i]; 
		}
		
		return $named_array;
	}
		
?>