<?php
    //  data_total.php
    // Renders button to produce total databse
?>

<!-- Show Database Button -->
<div class="btn-large" id="total_show"> Show Database </div>


<!-- Submit Button Handler -->
<script type="application/javascript">
    $('#total_show').on('click', function(){
        jQuery.ajax( { type: "POST", url: 'table_database.php',
            dataType: 'json', data : {
                start_month  : start_date().m, start_day  : start_date().d,
                start_year   : start_date().y, start_hour : start_time().h,
                start_minute : start_time().m, end_month  : end_date().m,
                end_day      : end_date().d,   end_year   : end_date().y,
                end_hour     : end_time().h,   end_minute : end_time().m
            }, error: function ( obj ) {
                $("#total-tab").html( obj.responseText );
            }
        } );
    })
</script>