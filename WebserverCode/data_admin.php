<?php
    //  data_admin.php
    // Renders admin controls
?>

<br>

<div id="admin_add" class="btn-large"> Add Person</div> <br> <br>

<form id="add_form" style="display:none" class = "col s12">
    <br>
    <div style="background: #fcfcfc;" class="row">
        <div style="background: #fcfcfc">
            <div style="background: #fcfcfc" class="input-field col s12">
                <input placeholder="Date" id="add_date" type="text" class="add_datepicker">
                <input placeholder="Time" id="add_time" type="text" class="add_timepicker">
                <div id="add_submit" class="btn"> Add Person </div> <br> <br>
            </div>
        </div>
    </div>
</form>

<div id="add_message" style = "display:none; font-size:30px;">
    The Person Has Been Added.
</div><br>

<div id="admin_remove" class="btn-large"> Remove Person</div> <br> <br>

<form id = "remove_form" style = "display:none" class="col s12" action="admin_functions.php" method="post">
    <label style="font-family:Verdana; font-size:12px;"> Select Counter : </label>
    <?php echo "<input id=\"admin_counter\" name=\"counter\" type=\"number\" min=\"1\" step=\"1\" max=\"$largestNumber\"/>" ?>
    <input id="admin_remove" type="hidden" name="remove" value="true"   />
    <input id="remove_submit" type="button" value="Submit"/><br><br>
</form>

<div id="remove_message" style = "display:none; font-size:30px;">
    The Person Has Been Removed.
</div><br>

<div id="admin_reset" class="btn-large"> Reset Database</div> <br>

<form id = "reset_form" style = "display:none" class="col s12">
    <label style="font-family:Verdana; font-size:30px; color:red"> Are You Sure You Wish To Reset The Database? </label><br>
    <input id="reset_submit" type="button" value="Submit"/><br><br><br>
</form>

<div id="reset_message" style = "display:none; font-size:30px;">
    The Database Has Been Reset.
</div>



<script>
    
    'use strict';
    
    $('.add_datepicker').datepicker();
    $('.add_timepicker').timepicker();

    // Parses add date into JSON object
    function add_date() {
        var date_string = $('#add_date').val() || "Jan 10, 2000";
        var arr = date_string.replace(',','').split(' ');
        return {
            'm' : [ 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'].indexOf( arr[0] ),
            'd' : parseInt( arr[1] ),
            'y' : parseInt( arr[2] )
        };
    }

    // Parses add time into JSON object
    function add_time() {
        var time_string = $('#add_time').val() || "01:00 AM";
        var arr = time_string.replace(':',' ').split(' ');
        return {
            'h' : parseInt( arr[0] ) + ( arr[2] == "AM" ? 0 : 12 ),
            'm' : parseInt( arr[1] )
        };
    }

    // Send jQuery request to submit user to database
    $("#add_submit").on( "click", function () {

        var ad = add_date();
        var at = add_time();

        var add_datestamp = ad.y + '-' + ( (ad.m<10) ? '0'+ad.m : ad.m ) + '-' + ( (ad.d<10) ? '0'+ad.d : ad.d )
        var add_timestamp = ( (at.h<10) ? '0'+at.h : at.h ) + ':' + ( (at.m<10) ? '0'+at.m : at.m ) +':00'

        jQuery.ajax( { 
            type: "POST", 
            url: 'admin_functions.php',
            dataType: 'json', data : {
                time : add_timestamp,
                date : add_datestamp,
                add  : "true"
            }, error : function() {
                $('#add_form').toggle(false);
                $('#remove_form').toggle(false);
                $('#reset_form').toggle(false);
                $('#reset_message').toggle(false);
                $('#add_message').toggle();
                $('#remove_message').toggle(false);
            }
        } );
    } );

    // Send jQuery request to remove database
    function remove () {
        jQuery.ajax( { 
            type: "POST", 
            url: 'admin_functions.php',
            dataType: 'json', data : {
                counter : $('#admin_counter').val(),
                remove  : "true"
            }, error : function() {
                $('#add_form').toggle(false);
                $('#remove_form').toggle(false);
                $('#reset_form').toggle(false);
                $('#reset_message').toggle(false);
                $('#add_message').toggle(false);
                $('#remove_message').toggle();
            }
        } );
    }

    // Send jQuery request to reset database
    function reset () {
        jQuery.ajax( { type: "POST", url: 'admin_functions.php',
            dataType: 'json', data : {
            	reset : true
            }, error : function() {
                $('#add_form').toggle(false);
                $('#remove_form').toggle(false);
                $('#reset_form').toggle(false);
                $('#reset_message').toggle();
                $('#add_message').toggle(false);
                $('#remove_message').toggle(false);
            }
        } );
    }

    // Add button handler
    $('#admin_add').on('click', function() {
        $('#add_form').toggle();
        $('#remove_form').toggle(false);
        $('#reset_form').toggle(false);
        $('#reset_message').toggle(false);
        $('#add_message').toggle(false);
        $('#remove_message').toggle(false);
    } );

    // Remove button handler
    $('#admin_remove').on('click', function() {
        $('#add_form').toggle(false);
        $('#remove_form').toggle();
        $('#reset_form').toggle(false);
        $('#reset_message').toggle(false);
        $('#add_message').toggle(false);
        $('#remove_message').toggle(false);
    } );

    $('#remove_submit').on('click', remove);

    // Reset button handler
    $('#admin_reset').on('click', function() {
        $('#add_form').toggle(false);
        $('#remove_form').toggle(false);
        $('#reset_form').toggle();
        $('#reset_message').toggle(false);
        $('#add_message').toggle(false);
        $('#remove_message').toggle(false);
    } );

    $('#reset_submit').on('click', reset);

</script>
