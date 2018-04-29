<?php 

    // index.php
    // Main HTML file

?>

<!DOCTYPE html>
<html>

<head>

    <title> Walking Traffic Counter </title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body>

<div class="container">

    <h3> Walking Traffic Counter Data </h3>

    <form class="col s12">
        <br>
        <div style="background: #fcfcfc;" class="row">
            <div style="width:100%; height: 48px; color: #ee6e73; background: #f0f0f0; font-size: 16px; padding-top: 11px; padding-left: 30px;" > SELECT TIMEFRAME </div>
            <div style="background: #fcfcfc">
                <div style="background: #fcfcfc" class="input-field col s6">
                    <input placeholder="Start Date" id="start_date" type="text" class="updates datepicker">
                    <input placeholder="Start Time" id="start_time" type="text" class="updates timepicker">
                </div>
                <div style="background: #fcfcfc" class="input-field col s6">
                    <input placeholder="End Date" id="end_date" type="text" class="updates datepicker">
                    <input placeholder="End Time" id="end_time" type="text" class="updates timepicker">
                </div>
            </div>
        </div>
    </form>

    <br>
    <br>

    <ul style="background: #f0f0f0" class="tabs">
        <li class="tab col s2"><a href="#admin-tab"  > Admin   </a></li>
        <li class="tab col s2"><a href="#total-tab"  > Total   </a></li>
        <li class="tab col s2"><a href="#hourly-tab" > Hourly  </a></li>
        <li class="tab col s2"><a href="#daily-tab"  > Daily   </a></li>
        <li class="tab col s2"><a href="#weekly-tab" > Weekly  </a></li>
        <li class="tab col s2"><a href="#monthly-tab"> Monthly </a></li>
        <li class="tab col s2"><a href="#yearly-tab" > Yearly  </a></li>
    </ul>

    <div style="background: #fcfcfc" id="admin-tab"   class="col s12"></div>
    <div style="background: #fcfcfc" id="total-tab"   class="col s12"></div>
    <div style="background: #fcfcfc" id="hourly-tab"  class="col s12"></div>
    <div style="background: #fcfcfc" id="daily-tab"   class="col s12"></div>
    <div style="background: #fcfcfc" id="weekly-tab"  class="col s12"></div>
    <div style="background: #fcfcfc" id="monthly-tab" class="col s12"></div>
    <div style="background: #fcfcfc" id="yearly-tab"  class="col s12"></div>

</div>

<script type="text/javascript" src="./index.js"></script>

<script type="text/javascript">

    google.charts.load('current', {'packages':['corechart']});

    google.charts.setOnLoadCallback( render_charts() );

    $(document).ready( function() {

        $('.tabs').tabs({ onShow: render_charts});

        refresh_views();

        $('.updates').change( refresh_views );

        $('.datepicker').datepicker();

        $('.timepicker').timepicker();

    } );

</script>



</body>

</html>
