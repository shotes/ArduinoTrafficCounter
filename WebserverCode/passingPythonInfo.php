<?php
	//  passingPythonInfo.php
	// Submits data received from arduino to database
?>

<?php
	include 'configuration.php';
	// Allows the Arduino to send data to the database via Python. 
    $db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
    or die("Error Connecting.");

    $sql = "INSERT INTO `".getTableName()."`(`date`, `time`) VALUES ( '" . $_POST["date"] . "', '" . $_POST["time"] . "')";

    mysqli_query($db, $sql);

?>