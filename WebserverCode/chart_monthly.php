<?php
	//  chart_monthly.php
	// Aquires data and calculates statistics for monthly information
?>

<?php
	include 'configuration.php';
	//Gets all of the data and stores in an nested associative array ordered like this...
	//hourly_array[year][month] = 'number of people that triggered sensor in this month'
	function accessData($start_year, $end_year, $start_month, $end_month){
		$monthly_array = array();
		
		//Input Validation
		if(!ctype_digit($start_year) && !is_int($start_year)){
			return $monthly_array;
		}else if(!ctype_digit($end_year) && !is_int($end_year)){
			return $monthly_array;
		}else if(!ctype_digit($start_month) && !is_int($start_month)){
			return $monthly_array;
		}else if(!ctype_digit($end_month) && !is_int($end_month)){
			return $monthly_array;
		}
		
		//Connecting to database.
		$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
 		or die("Error Connecting.");
 		
 		//SQL request for 'date' column that is between two dates, and is accounting for months that have a different number of days. 
 		$thirty_one = [1, 3, 5, 7, 8, 10, 12];
 		$thirty = [4, 6, 9, 11];
 		if(in_array($end_month, $thirty_one)){
 			$sql = "SELECT `date` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-1' AND '". $end_year ."-". $end_month ."-31'";
 		}else if(in_array($end_month, $thirty)){
 			$sql = "SELECT `date` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-1' AND '". $end_year ."-". $end_month ."-30'";
 		}else{
 			$sql = "SELECT `date` FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-1' AND '". $end_year ."-". $end_month ."-28'";
 		}
 		
 		$result = mysqli_query($db, $sql);
 		
 		//Fills monthly_array.
 		while($row = mysqli_fetch_assoc($result)){
 			//Explodes 'date' so that we can get 'year' and 'month'.
 			$date_explode = explode('-', $row['date']);
 			$year = (int)$date_explode[0];
 			$month = (int)$date_explode[1];
 			
 			//Initializing unique year arrays.
 			if(!array_key_exists($year, $monthly_array)){
				$monthly_array[$year] = array();
			}
			
			//Initializing unique month arrays and filling them with values whenever a month has already been initialized.
			if(!array_key_exists($month, $monthly_array[$year])){
				$monthly_array[$year][$month] = 1;
			}else{
				$monthly_array[$year][$month] = $monthly_array[$year][$month] + 1;
			}
 		}
 		
 		return $monthly_array;			
	}
		
	//Will return an associative array with the mean value for each hour.
	//For example, $mean_array[2] = 5 means that the mean amount of people that triggered the sensor in February is 5 people. 
	//It goes from 1-12 corresponding to months. 	
	function getMeanMonths($start_year, $end_year, $start_month, $end_month){
		//Declaring arrays
		$MONTHS_IN_YEAR = 13;	
		$monthly_array = accessData($start_year, $end_year, $start_month, $end_month);
		$sum_array = array();
		$divideBy_array = array();
		$mean_array = array();
		
		//Initializing the arrays.
		//$sum_array will hold the number of people that have crossed in that specific month, for the entire timeframe specified.
		//$dividBy_array will holder the number of unique months that were pulled from for each month (unique regarding year) 
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			$sum_array[$i] = 0;
			$divideBy_array[$i] = 0;
		}
		
		//This fills the previously described associative arrays, $sum_array and $divideBy_array.
		foreach($monthly_array as $year => $temp1){
			foreach($monthly_array[$year] as $month => $value){
				$sum_array[$month] += $value;
				$divideBy_array[$month] += 1;
			}
		}
		
		//This creates the associative array $mean_array dividing the $sum_array by the $divideBy_array, with the index
		//decided by the specific month that has its mean being caluclated. 
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			$mean_array[$i] = (int)($sum_array[$i]/$divideBy_array[$i]);
		}
			
		//Any values that are still null will be set to 0.	
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			if($mean_array[$i] == NULL)
				$mean_array[$i] = 0;
		}
			
 		return $mean_array;
	}
	
	//Will return an associative array with the median value for each month.
	//For example, $median_array[2] = 5 means that the median amount of people that triggered the sensor in February is 5 people. 
	//It goes from 1-12 corresponding to months.
	function getMedianMonths($start_year, $end_year, $start_month, $end_month){
		//Declaring arrays
		$MONTHS_IN_YEAR = 13;
		$monthly_array = accessData($start_year, $end_year, $start_month, $end_month);
		$all_months_array = array();
		$median_array = array();
		
		//Initializing the array that will hold every single count for each month. 
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			$all_months_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific month stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, month).
		foreach($monthly_array as $year => $temp1){
			foreach($monthly_array[$year] as $month => $value){
				array_push($all_months_array[$month], $value);
			}
		}
		
		//This will sort each array for each month value in descending order, then it will set the median array value for that month
		//by grabbing the middle value for each array from $all_months_array.
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			sort($all_months_array[$i]);
			$median_array[$i] = $all_months_array[$i][floor(count($all_months_array[$i])/2)];
		}
		
		//Any values that are still null will be set to 0.
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			if($median_array[$i] == NULL)
				$median_array[$i] = 0;
		}
		
		return $median_array;
	}
	
	//Will return an associative array with the largest value for each month.
	//For example, $max_array[2] = 5 means that the largest amount of people that triggered the sensor in February is 5 people. 
	//It goes from 1-12 corresponding to months.
	function getMaxMonths($start_year, $end_year, $start_month, $end_month){
		//Declaring arrays
		$MONTHS_IN_YEAR = 13;
		$monthly_array = accessData($start_year, $end_year, $start_month, $end_month);
		$all_months_array = array();
		$max_array = array();
	
		//Initializing the array that will hold every single count for each month. 
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			$all_months_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific month stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, month).
		foreach($monthly_array as $year => $temp1){
			foreach($monthly_array[$year] as $month => $value){
				array_push($all_months_array[$month], $value);
			}
		}
		
		//This will sort each array for each month value in ascending order, then it will set the maximum array value for that month
		//by grabbing the first value for each array from $all_months_array.
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			rsort($all_months_array[$i]);
			$max_array[$i] = $all_months_array[$i][0];
		}
		
		//Any values that are still null will be set to 0.
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			if($max_array[$i] == NULL)
				$max_array[$i] = 0;
		}
		
		return $max_array;
	}
	
	//Will return an associative array with the smallest value for each month.
	//For example, $min_array[2] = 5 means that the smallest amount of people that triggered the sensor on February is 5 people. 
	//It goes from 1-12 corresponding to months.
	function getMinMonths($start_year, $end_year, $start_month, $end_month){
		//Declaring arrays
		$MONTHS_IN_YEAR = 13;
		$monthly_array = accessData($start_year, $end_year, $start_month, $end_month);
		$all_months_array = array();
		$min_array = array();
	
		//Initializing the array that will hold every single count for each month. 
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			$all_months_array[$i] = array();
		}
		
		//This creates an associative array, with all the results for a specific month stored in an array contained within the associative array.
		//A value indicates how many people triggered the sensor for that specific time period (year, month).
		foreach($monthly_array as $year => $temp1){
			foreach($monthly_array[$year] as $month => $value){
				array_push($all_months_array[$month], $value);
			}
		}
		
		//This will sort each array for each month value in descending order, then it will set the minimum array value for that month
		//by grabbing the first value for each array from $all_months_array.
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			sort($all_months_array[$i]);
			$min_array[$i] = $all_months_array[$i][0];
		}
		
		//Any values that are still null will be set to 0. 
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			if($min_array[$i] == NULL)
				$min_array[$i] = 0;
		}
		
		return $min_array;
	}
	
	//Attaches names to the associative array keys, so rather than having arr[1] = 2, you'll have arr[Jan] = 2.
	function attachMonthsNames($requestedStat, $start_year, $end_year, $start_month, $end_month){
		//Deciding which functions to call to get the array that needs to have its keys adjusted. 
		$MONTHS_IN_YEAR = 13;
		if($requestedStat == "mean"){
			$temp_array = getMeanMonths($start_year, $end_year, $start_month, $end_month);
		}else if($requestedStat == "median"){
			$temp_array = getMedianMonths($start_year, $end_year, $start_month, $end_month);
		}else if($requestedStat == "max"){
			$temp_array = getMaxMonths($start_year, $end_year, $start_month, $end_month);
		}else if($requestedStat == "min"){
			$temp_array = getMinMonths($start_year, $end_year, $start_month, $end_month);
		};
		
		//Replacing integers with their string month names. 
		$month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		$named_array = array();
		for($i = 1; $i < $MONTHS_IN_YEAR; $i++){
			$named_array[$month_names[$i - 1]] = $temp_array[$i]; 
		}
		
		return $named_array;
	}
	
?>