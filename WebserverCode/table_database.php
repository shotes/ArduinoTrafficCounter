<?php
    //  table_database.php
    // Redners tables for total database
?>

<?php
	include 'configuration.php';
	// --- Get Post Parameters ---

	$start_month  = $_POST['start_month'];
	$start_day    = $_POST['start_day'];
	$start_year   = $_POST['start_year'];
	$start_hour   = $_POST['start_hour'];
	$start_minute = $_POST['start_minute'];
	$end_month    = $_POST['end_month'];
	$end_day      = $_POST['end_day'];
	$end_year     = $_POST['end_year'];
	$end_hour     = $_POST['end_hour'];
	$end_minute   = $_POST['end_minute'];
	
	$start_month = (int)$start_month;
	$start_month++;
	$start_month = (string)$start_month;
	
	$end_month = (int)$end_month;
	$end_month++;
	$end_month = (string)$end_month;
	// --- Connect to Database ---

	$db = mysqli_connect(getHostUrl(), getUsername(), getPassword(), getDatabaseName())
	or die("Error Connecting.");

	// --- Query Database ---

	$sql = "SELECT COUNT(*) as total FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-". $start_day ."' AND '". $end_year ."-". $end_month ."-". $end_day ."'";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($result);

?>

<!-- Output Totals -->
<br>
<br>
Total matched entries: <?php echo $row['total']; ?>
<br>
<br>

<?php
	
	// --- Query Database ---

	$sql = "SELECT * FROM `".getTableName()."` WHERE `date` BETWEEN '". $start_year ."-". $start_month ."-". $start_day ."' AND '". $end_year ."-". $end_month ."-". $end_day ."'";

	$result = mysqli_query($db, $sql);
	$total_timeframe_array = array();

	$start_date = $start_year ."-". $start_month ."-". $start_day;
	$end_date = $end_year ."-". $end_month ."-". $end_day;
 		
 	// --- Output table Headers ---
?> 			
<table style="width:30%; border:1px solid black; border-collapse: collapse;">
<thead>
<tr>
	<td style="border:1px solid black; border-collapse: collapse;">Counter</td>
	<td style="border:1px solid black; border-collapse: collapse;">Hour</td>
	<td style="border:1px solid black; border-collapse: collapse;">Day</td>
	<td style="border:1px solid black; border-collapse: collapse;">Week</td>
	<td style="border:1px solid black; border-collapse: collapse;">Month</td>
	<td style="border:1px solid black; border-collapse: collapse;">Year</td>
</tr>
</thead>
<tbody>
<?php
	
		// --- Output Table Rows ---
 		
 		while($row = mysqli_fetch_assoc($result))
 		{
 			$date_explode = explode('-', $row['date']);
 			$time_explode = explode(':', $row['time']);
 			$year  = (int)$date_explode[0];
 			$month = (int)$date_explode[1]; 
 			$day   = (int)$date_explode[2];
 			$hour  = (int)$time_explode[0];
 			
 			$getting_week_date = mktime(0, 0, 0, $date_explode[1], $date_explode[2], $date_explode[0]);
 			$week = (int)date('W', $getting_week_date);

 			if($hour == 12)
 			{
 				$hour = 0;
 			}
 			else if($hour == 24)
 			{
 				$hour = 12;
 			}
 			
 			if($start_data == $row['date'])
 			{
 				if($hour < $start_hour)
 					continue;
 			}
 			
 			if($end_data == $row['date'])
 			{
 				if($hour > $end_hour)
 					continue;
 			}
?>
        	<tr>
          	 	<td style="border:1px solid black; border-collapse: collapse;"><?php echo $row['counter']?></td>
            	<td style="border:1px solid black; border-collapse: collapse;"><?php echo $hour?></td>
            	<td style="border:1px solid black; border-collapse: collapse;"><?php echo $day?></td>
            	<td style="border:1px solid black; border-collapse: collapse;"><?php echo $week?></td>
            	<td style="border:1px solid black; border-collapse: collapse;"><?php echo $month?></td>
            	<td style="border:1px solid black; border-collapse: collapse;"><?php echo $year?></td>
        	</tr>
    		
<?php
 	}
?>

</tbody>
</table>
