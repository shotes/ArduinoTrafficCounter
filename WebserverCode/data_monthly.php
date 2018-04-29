
<?php
    //  data_monthly.php
    // Redners tables for monthly statistics
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

    include 'chart_monthly.php';

?>


<!-- Output Monthly Table -->

<br>

<table class="striped centered" >
    <thead>
    <tr>
        <th>  </th>
        <th>    Jan    </th>
        <th>    Feb    </th>
        <th>    Mar    </th>
        <th>    Apr    </th>
        <th>    May    </th>
        <th>    Jun    </th>
        <th>    Jul    </th>
        <th>    Aug    </th>
        <th>    Sep    </th>
        <th>    Oct    </th>
        <th>    Nov    </th>
        <th>    Dec    </th>
    </tr>
    </thead>

    <tbody>
    <tr>
    <?php $mean_array = getMeanMonths($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1); ?>
        <td> Mean </td>
        <td>  <?php echo $mean_array[1]; ?>   </td>
        <td>  <?php echo $mean_array[2]; ?>    </td>
        <td>  <?php echo $mean_array[3]; ?>    </td>
        <td>  <?php echo $mean_array[4]; ?>    </td>
        <td>  <?php echo $mean_array[5]; ?>    </td>
        <td>  <?php echo $mean_array[6]; ?>    </td>
        <td>  <?php echo $mean_array[7]; ?>    </td>
        <td>  <?php echo $mean_array[8]; ?>    </td>
        <td>  <?php echo $mean_array[9]; ?>    </td>
        <td>  <?php echo $mean_array[10]; ?>   </td>
        <td>  <?php echo $mean_array[11]; ?>    </td>
        <td>  <?php echo $mean_array[12]; ?>    </td>
    </tr>
    <tr>
    <?php $median_array = getMedianMonths($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1); ?>
        <td> Median </td>
        <td>  <?php echo $median_array[1]; ?>   </td>
        <td>  <?php echo $median_array[2]; ?>    </td>
        <td>  <?php echo $median_array[3]; ?>    </td>
        <td>  <?php echo $median_array[4]; ?>    </td>
        <td>  <?php echo $median_array[5]; ?>    </td>
        <td>  <?php echo $median_array[6]; ?>    </td>
        <td>  <?php echo $median_array[7]; ?>    </td>
        <td>  <?php echo $median_array[8]; ?>    </td>
        <td>  <?php echo $median_array[9]; ?>    </td>
        <td>  <?php echo $median_array[10]; ?>   </td>
        <td>  <?php echo $median_array[11]; ?>    </td>
        <td>  <?php echo $median_array[12]; ?>    </td>
    </tr>
    <tr>
    <?php $max_array = getMaxMonths($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1); ?>
        <td> Max </td>
        <td>  <?php echo $max_array[1]; ?>   </td>
        <td>  <?php echo $max_array[2]; ?>    </td>
        <td>  <?php echo $max_array[3]; ?>    </td>
        <td>  <?php echo $max_array[4]; ?>    </td>
        <td>  <?php echo $max_array[5]; ?>    </td>
        <td>  <?php echo $max_array[6]; ?>    </td>
        <td>  <?php echo $max_array[7]; ?>    </td>
        <td>  <?php echo $max_array[8]; ?>    </td>
        <td>  <?php echo $max_array[9]; ?>    </td>
        <td>  <?php echo $max_array[10]; ?>   </td>
        <td>  <?php echo $max_array[11]; ?>    </td>
        <td>  <?php echo $max_array[12]; ?>    </td>
    </tr>
    <tr>
    <?php $min_array = getMinMonths($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1); ?>
        <td> Min </td>
        <td>  <?php echo $min_array[1]; ?>   </td>
        <td>  <?php echo $min_array[2]; ?>    </td>
        <td>  <?php echo $min_array[3]; ?>    </td>
        <td>  <?php echo $min_array[4]; ?>    </td>
        <td>  <?php echo $min_array[5]; ?>    </td>
        <td>  <?php echo $min_array[6]; ?>    </td>
        <td>  <?php echo $min_array[7]; ?>    </td>
        <td>  <?php echo $min_array[8]; ?>    </td>
        <td>  <?php echo $min_array[9]; ?>    </td>
        <td>  <?php echo $min_array[10]; ?>   </td>
        <td>  <?php echo $min_array[11]; ?>    </td>
        <td>  <?php echo $min_array[12]; ?>    </td>
    </tr>
    </tbody>
</table>

<br>
<br>

<div id = "monthly_mean"></div>
<div id = "monthly_median"></div>
<div id = "monthly_max"></div>
<div id = "monthly_min"></div>

<br>
<br>


<script type="application/javascript">

    create_bar_chart( "monthly_mean", "Monthly Mean", 
    <?php 
    	$mean_array = attachMonthsNames('mean', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1);
    	echo json_encode($mean_array);
    	
    ?> );

    create_bar_chart( "monthly_median", "Monthly Median", 
    <?php 
    	$median_array = attachMonthsNames('median', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1);
    	echo json_encode($median_array);
    ?> );
    
    create_bar_chart( "monthly_max", "Monthly Max", 
    <?php 
    	$max_array = attachMonthsNames('max', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1);
    	echo json_encode($max_array);
    ?> );
    
    create_bar_chart( "monthly_min", "Monthly Min", 
    <?php 
    	$min_array = attachMonthsNames('min', $start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1);
    	echo json_encode($min_array);
    ?> );

</script>