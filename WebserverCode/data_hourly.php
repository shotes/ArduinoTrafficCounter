<?php
    //  data_hourly.php
    // Redners tables for hourly statistics
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

    include 'chart_hourly.php';

?>

<br>

<!-- Output Hourly Table -->

<table class="striped centered">
    <thead>
    <tr>
        <th>  </th>
        <th> 12 AM </th>
        <th> 1 AM </th>
        <th> 2 AM </th>
        <th> 3 AM </th>
        <th> 4 AM </th>
        <th> 5 AM </th>
        <th> 6 AM </th>
        <th> 7 AM </th>
        <th> 8 AM </th>
        <th> 9 AM </th>
        <th> 10 AM </th>
        <th> 11 AM </th>
    </tr>
    </thead>

    <tbody>
    <tr>
    <?php $mean_array = getMeanHours($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day, $start_hour, $end_hour); ?>
        <td> Mean </td>
        <td> <?php echo $mean_array[0] ?> </td>
        <td> <?php echo $mean_array[1] ?> </td>
        <td> <?php echo $mean_array[2] ?> </td>
        <td> <?php echo $mean_array[3] ?> </td>
        <td> <?php echo $mean_array[4] ?> </td>
        <td> <?php echo $mean_array[5] ?> </td>
        <td> <?php echo $mean_array[6] ?> </td>
        <td> <?php echo $mean_array[7] ?> </td>
        <td> <?php echo $mean_array[8] ?> </td>
        <td> <?php echo $mean_array[9] ?> </td>
        <td> <?php echo $mean_array[10] ?> </td>
        <td> <?php echo $mean_array[11] ?> </td>
    </tr>
    <tr>
    <?php $median_array = getMedianHours($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day, $start_hour, $end_hour); ?>
        <td> Median </td>
        <td> <?php echo $median_array[0] ?> </td>
        <td> <?php echo $median_array[1] ?> </td>
        <td> <?php echo $median_array[2] ?> </td>
        <td> <?php echo $median_array[3] ?> </td>
        <td> <?php echo $median_array[4] ?> </td>
        <td> <?php echo $median_array[5] ?> </td>
        <td> <?php echo $median_array[6] ?> </td>
        <td> <?php echo $median_array[7] ?> </td>
        <td> <?php echo $median_array[8] ?> </td>
        <td> <?php echo $median_array[9] ?> </td>
        <td> <?php echo $median_array[10] ?> </td>
        <td> <?php echo $median_array[11] ?> </td>
    </tr>
    <tr>
    <?php $max_array = getMaxHours($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day, $start_hour, $end_hour); ?>
        <td> Maximum </td>
        <td> <?php echo $max_array[0] ?> </td>
        <td> <?php echo $max_array[1] ?> </td>
        <td> <?php echo $max_array[2] ?> </td>
        <td> <?php echo $max_array[3] ?> </td>
        <td> <?php echo $max_array[4] ?> </td>
        <td> <?php echo $max_array[5] ?> </td>
        <td> <?php echo $max_array[6] ?> </td>
        <td> <?php echo $max_array[7] ?> </td>
        <td> <?php echo $max_array[8] ?> </td>
        <td> <?php echo $max_array[9] ?> </td>
        <td> <?php echo $max_array[10] ?> </td>
        <td> <?php echo $max_array[11] ?> </td>
    </tr>
    <tr>
    <?php $min_array = getMinHours($start_year, $end_year, (int)$start_month + 1, (int)$end_month + 1, $start_day, $end_day, $start_hour, $end_hour); ?>
        <td> Minimum </td>
        <td> <?php echo $min_array[0] ?> </td>
        <td> <?php echo $min_array[1] ?> </td>
        <td> <?php echo $min_array[2] ?> </td>
        <td> <?php echo $min_array[3] ?> </td>
        <td> <?php echo $min_array[4] ?> </td>
        <td> <?php echo $min_array[5] ?> </td>
        <td> <?php echo $min_array[6] ?> </td>
        <td> <?php echo $min_array[7] ?> </td>
        <td> <?php echo $min_array[8] ?> </td>
        <td> <?php echo $min_array[9] ?> </td>
        <td> <?php echo $min_array[10] ?> </td>
        <td> <?php echo $min_array[11] ?> </td>
    </tr>
    </tbody>
</table>

<br>


<table class="striped centered">
    <thead>
    <tr>
        <th>  </th>
        <th> 12 PM </th>
        <th> 1 PM </th>
        <th> 2 PM </th>
        <th> 3 PM </th>
        <th> 4 PM </th>
        <th> 5 PM </th>
        <th> 6 PM </th>
        <th> 7 PM </th>
        <th> 8 PM </th>
        <th> 9 PM </th>
        <th> 10 PM </th>
        <th> 11 PM </th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td> Mean </td>
        <td> <?php echo $mean_array[12] ?> </td>
        <td> <?php echo $mean_array[13] ?> </td>
        <td> <?php echo $mean_array[14] ?> </td>
        <td> <?php echo $mean_array[15] ?> </td>
        <td> <?php echo $mean_array[16] ?> </td>
        <td> <?php echo $mean_array[17] ?> </td>
        <td> <?php echo $mean_array[18] ?> </td>
        <td> <?php echo $mean_array[19] ?> </td>
        <td> <?php echo $mean_array[20] ?> </td>
        <td> <?php echo $mean_array[21] ?> </td>
        <td> <?php echo $mean_array[22] ?> </td>
        <td> <?php echo $mean_array[23] ?> </td>
    </tr>
    <tr>
        <td> Median </td>
        <td> <?php echo $median_array[12] ?> </td>
        <td> <?php echo $median_array[13] ?> </td>
        <td> <?php echo $median_array[14] ?> </td>
        <td> <?php echo $median_array[15] ?> </td>
        <td> <?php echo $median_array[16] ?> </td>
        <td> <?php echo $median_array[17] ?> </td>
        <td> <?php echo $median_array[18] ?> </td>
        <td> <?php echo $median_array[19] ?> </td>
        <td> <?php echo $median_array[20] ?> </td>
        <td> <?php echo $median_array[21] ?> </td>
        <td> <?php echo $median_array[22] ?> </td>
        <td> <?php echo $median_array[23] ?> </td>
    </tr>
    <tr>
        <td> Maximum </td>
        <td> <?php echo $max_array[12] ?> </td>
        <td> <?php echo $max_array[13] ?> </td>
        <td> <?php echo $max_array[14] ?> </td>
        <td> <?php echo $max_array[15] ?> </td>
        <td> <?php echo $max_array[16] ?> </td>
        <td> <?php echo $max_array[17] ?> </td>
        <td> <?php echo $max_array[18] ?> </td>
        <td> <?php echo $max_array[19] ?> </td>
        <td> <?php echo $max_array[20] ?> </td>
        <td> <?php echo $max_array[21] ?> </td>
        <td> <?php echo $max_array[22] ?> </td>
        <td> <?php echo $max_array[23] ?> </td>
    </tr>
    <tr>
        <td> Minimum </td>
        <td> <?php echo $min_array[12] ?> </td>
        <td> <?php echo $min_array[13] ?> </td>
        <td> <?php echo $min_array[14] ?> </td>
        <td> <?php echo $min_array[15] ?> </td>
        <td> <?php echo $min_array[16] ?> </td>
        <td> <?php echo $min_array[17] ?> </td>
        <td> <?php echo $min_array[18] ?> </td>
        <td> <?php echo $min_array[19] ?> </td>
        <td> <?php echo $min_array[20] ?> </td>
        <td> <?php echo $min_array[21] ?> </td>
        <td> <?php echo $min_array[22] ?> </td>
        <td> <?php echo $min_array[23] ?> </td>
    </tr>
    </tbody>
</table>

<br>
<br>


<div id="hourly_mean"> </div>
<div id="hourly_median"> </div>
<div id="hourly_max"> </div>
<div id="hourly_min"> </div>



<br>
<br>

<script type="application/javascript">

    create_line_chart( "hourly_mean", "Hourly Mean", 
    <?php
    	echo json_encode($mean_array);
    ?>);

    create_line_chart( "hourly_median", "Hourly Median", 
    <?php
    	echo json_encode($median_array);
    ?>);

    create_line_chart( "hourly_max", "Hourly Max", 
    <?php
    	echo json_encode($max_array);
    ?>);
    
    create_line_chart( "hourly_min", "Hourly Min", 
    <?php
    	echo json_encode($min_array);
    ?>);



</script>