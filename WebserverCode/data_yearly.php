<?php
    //  chart_yearly.php
    // Aquires data and calculates statistics for yearly information
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

include 'chart_yearly.php';

?>

<!-- Render Yearly Table -->

<br>
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
        <td> <?php echo getMeanYearlyCount($start_year, $end_year); ?> </td>
        <td> <?php echo getMedianYear($start_year, $end_year); ?> </td>
        <td> <?php echo getMaxYear($start_year, $end_year); ?> </td>
        <td> <?php echo getMinYear($start_year, $end_year); ?> </td>
    </tr>
    </tbody>
</table>

<br>
<br>

<div id = "yearly_mean"></div>
<br>
<br>

<script type="application/javascript">

    create_bar_chart( "yearly_mean", "Daily Mean", 
    <?php 
    	$mean_array = getSeparateYearlyCounts($start_year, $end_year);
    	echo json_encode($mean_array);
    ?>);

</script>