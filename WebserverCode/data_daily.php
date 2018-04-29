<?php
    //  data_daily.php
    // Redners tables for daily statistics
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
    include 'chart_daily.php';
?>

<br>

<!-- Output DOW Table -->

<table class="striped centered" >
    <thead>
    <tr>
        <th> </th>
        <th> Sunday    </th>
        <th> Monday    </th>
        <th> Tuesday   </th>
        <th> Wednesday </th>
        <th> Thursday  </th>
        <th> Friday    </th>
        <th> Saturday  </th>
    </tr>
    </thead>

    <tbody>
    <tr>
    <?php $mean_array = getMeanDays($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?>
        <td> Mean </td>
        <td>  <?php echo $mean_array[0] ?>  </td>
        <td>  <?php echo $mean_array[1] ?>  </td>
        <td>  <?php echo $mean_array[2] ?>  </td>
        <td>  <?php echo $mean_array[3] ?>  </td>
        <td>  <?php echo $mean_array[4] ?>  </td>
        <td>  <?php echo $mean_array[5] ?>  </td>
        <td>  <?php echo $mean_array[6] ?>  </td>
    </tr>
    <tr>
    <?php $median_array = getMedianDays($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?>
        <td> Median </td>
        <td>  <?php echo $median_array[0] ?>  </td>
        <td>  <?php echo $median_array[1] ?>  </td>
        <td>  <?php echo $median_array[2] ?>  </td>
        <td>  <?php echo $median_array[3] ?>  </td>
        <td>  <?php echo $median_array[4] ?>  </td>
        <td>  <?php echo $median_array[5] ?>  </td>
        <td>  <?php echo $median_array[6] ?>  </td>
    </tr>
    <tr>
    <?php $max_array = getMaxDays($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?>
    	<td> Max </td>
        <td>  <?php echo $max_array[0] ?>  </td>
        <td>  <?php echo $max_array[1] ?>  </td>
        <td>  <?php echo $max_array[2] ?>  </td>
        <td>  <?php echo $max_array[3] ?>  </td>
        <td>  <?php echo $max_array[4] ?>  </td>
        <td>  <?php echo $max_array[5] ?>  </td>
        <td>  <?php echo $max_array[6] ?>  </td>
    </tr>
    <tr>
    <?php $min_array = getMinDays($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day); ?>
        <td> Min </td>
        <td>  <?php echo $min_array[0] ?>  </td>
        <td>  <?php echo $min_array[1] ?>  </td>
        <td>  <?php echo $min_array[2] ?>  </td>
        <td>  <?php echo $min_array[3] ?>  </td>
        <td>  <?php echo $min_array[4] ?>  </td>
        <td>  <?php echo $min_array[5] ?>  </td>
        <td>  <?php echo $min_array[6] ?>  </td>
    </tr>
    </tbody>
</table>

<br>
<br>

<div id = "daily_mean"></div>
<div id = "daily_median"></div>
<div id = "daily_max"></div>
<div id = "daily_min"></div>

<br>
<br>

<script type="application/javascript">

    create_bar_chart( "daily_mean", "Daily Mean", 
    <?php
    	$mean_array = attachDaysNames('mean', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($mean_array);
    ?> );

    create_bar_chart( "daily_median", "Daily Median", 
    <?php
    	$median_array = attachDaysNames('median', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($median_array);
    ?> );

    create_bar_chart( "daily_max", "Daily Max", 
    <?php
    	$max_array = attachDaysNames('max', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($max_array);
    ?> );
    
    create_bar_chart( "daily_min", "Daily Min", 
    <?php
    	$min_array = attachDaysNames('min', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day);
    	echo json_encode($min_array);
    ?> );


</script>