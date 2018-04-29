<?php
	//  admin_functions.php
	// processes requests to perform various admin functions
?>

<?php
	include 'configuration.php';
	// --- Get Post Parameters ---

	$reset   = $_POST["reset"];
	$add     = $_POST['add'];
	$remove  = $_POST['remove'];
	$date    = $_POST['date'];
	$time    = $_POST['time'];
	$counter = $_POST['counter'];

	// Reset if asked to
	if($reset)
	{
		reset_db();
	}
	// Add user if asked to
	elseif($add)
	{
		add_db($date, $time);
	}
	// Remove a user if asked to
	elseif($remove)
	{
		remove_db($counter);
	}
	
 	// Returns connection to mysql database
	function loadDatabase() {
		$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
			or die("Error Connecting.");
 			
		return $db;
	}

	// Resets database
	function reset_db() {
		$db = loadDatabase();
	
		$sql = "DROP TABLE ".getTableName();

		mysqli_query($db, $sql);

		$sql = "CREATE TABLE `".getDatabaseName()."`.`".getTableName()."` (
					`counter` INT NULL AUTO_INCREMENT ,
					`date` DATE NOT NULL , 
					`time` TIME NOT NULL ,
					PRIMARY KEY (`counter`)
		   			) ENGINE = InnoDB;";
		mysqli_query($db, $sql);
	}

	// Adds a user to the database
	function add_db($date, $time) {
		$db = loadDatabase();
		
		$date_explode = explode('-', $date);
		$time_explode = explode(':', $time);
		$year = $date_explode[0];
		$month = (int)$date_explode[1] + 1;
		$day = $date_explode[2];
		$hour = $time_explode[0];
		
		if($hour == 12)
		{
			$hour = 0;
		}
		else if($hour == 24)
		{
			$hour = 12;
		}
		
		$date = $year ."-". $month ."-". $day;
		$time = $hour .":". $time_explode[1] .":". $time_explode[2];
			
		$sql = "INSERT INTO `".getTableName()."`(`date`, `time`) VALUES ('". $date ."', '". $time ."');";

    	mysqli_query($db, $sql);
	}

	// Remove a user from the database
	function remove_db($counter) {
		$db = loadDatabase();
    	$sql = "DELETE FROM `".getTableName()."` WHERE `counter` = ". $counter;
    	mysqli_query($db, $sql);
	}

?>