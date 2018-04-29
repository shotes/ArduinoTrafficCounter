<?php
    //  data_weekly.php
    // Redners tables for weekly statistics
?>

<?php

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

	include 'chart_weekly.php';
?>

<!-- Output Weekly Table -->

<br>

<table class="centered">
    <thead>
    <tr>
        <th> Mean    </th>
        <th> Median  </th>
        <th> Maximum </th>
        <th> Minimum </th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td> <?php echo getOverallWeekMean(   $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?> </td>
        <td> <?php echo getOverallMedianWeek( $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?> </td>
        <td> <?php echo getOverallMaxWeek(    $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?> </td>
        <td> <?php echo getOverallMinWeek(    $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?> </td>
    </tr>
    </tbody>
</table>

<br>
<br>

<div id = "weekly_mean"></div>
<div id = "weekly_median"></div>
<div id = "weekly_max"></div>
<div id = "weekly_min"></div>

<br>
<br>


<!-- Output Table -->

<script type="application/javascript">

    create_line_chart( "weekly_mean", "Weekly Mean", 
    <?php 
    	$mean_array = getMeanWeeks($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($mean_array);
    ?> );

    create_line_chart( "weekly_median", "Weekly Median", 
    <?php 
    	$median_array = getMedianWeeks($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($median_array);
    ?> );

    create_line_chart( "weekly_max", "Weekly Max", 
    <?php 
    	$max_array = getMaxWeeks($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($max_array);
    ?> );
    
    create_line_chart( "weekly_min", "Weekly Min", 
    <?php 
    	$min_array = getMinWeeks($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($min_array);
    ?> );

</script>
