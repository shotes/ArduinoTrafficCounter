// index.js
// Contains helper javascript functions for index


// --- Chart Maintanance ---

var chart_datasets = [];

// Clears chart data
function clear_charts() 
{
    chart_datasets = [];
}

// Adds data for a new bar chart
function create_bar_chart( id, title, data_obj ) 
{
    chart_datasets.push( [id, title, data_obj, 0] );
}

// Adds data for a new line chart
function create_line_chart( id, title, data_obj ) 
{
    chart_datasets.push( [id, title, data_obj, 1] );
}

// Renders all chart data
function render_charts() 
{

    setTimeout(function() {
        for( var i = 0; i < chart_datasets.length; i++ )
        {
            var data = new google.visualization.DataTable();

            data.addColumn('string', 'stat');
            data.addColumn('number', 'value');

            var data_arr = [];
            Object.keys( chart_datasets[i][2] ).map(function(keyName, keyIndex) {
                data_arr.push( [ keyName, chart_datasets[i][2][keyName] ] )
            });

            data.addRows( data_arr );


            var chart = chart_datasets[i][3] ?  new google.visualization.LineChart(document.getElementById( chart_datasets[i][0] )) : new google.visualization.BarChart(document.getElementById( chart_datasets[i][0] ));

            chart.draw( data, {
                'title':chart_datasets[i][1],
                'width' : $("#hourly-tab").width() - 20
            } );
        }
    }, 1000);

}



// --- Date and Time Parsing ---

// Parses start date into JSON object
function start_date() 
{
    var date_string = $('#start_date').val() || "Jan 10, 2000";
    var arr = date_string.replace(',','').split(' ');
    return {
        'm' : [ 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'].indexOf( arr[0] ),
        'd' : parseInt( arr[1] ),
        'y' : parseInt( arr[2] )
    };
}

// Parses start time into JSON object
function start_time() 
{
    var time_string = $('#start_time').val() || "01:00 AM";
    var arr = time_string.replace(':',' ').split(' ');
    return {
        'h' : parseInt( arr[0] ) + ( arr[2] == "AM" ? 0 : 12 ),
        'm' : parseInt( arr[1] )
    };
}

// Parses end date into JSON object
function end_date() 
{
    var date_string = $('#end_date').val() || "Jan 10, 2100";
    var arr = date_string.replace(',','').split(' ');
    return {
        'm' : [ 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'].indexOf( arr[0] ),
        'd' : parseInt( arr[1] ),
        'y' : parseInt( arr[2] )
    };
}

// Parses end time into JSON object
function end_time() 
{
    var time_string = $('#end_time').val() || "01:00 AM";
    var arr = time_string.replace(':',' ').split(' ');
    return{
        'h' : parseInt( arr[0] ) + ( arr[2] == "AM" ? 0 : 12 ),
        'm' : parseInt( arr[1] )
    };
}



// --- Tab Loading and Rendering ---

// Render admin tab
function write_admin(){
    jQuery.ajax( { type: "POST", url: 'data_admin.php',
        dataType: 'json', data : {},
        error: function ( obj ) {
            $("#admin-tab").html( obj.responseText );
        }
    } );
}

// Render total tab
function write_total () {
    jQuery.ajax( { type: "POST", url: 'data_total.php',
        dataType: 'json', data : {},
        error: function ( obj ) {
            $("#total-tab").html( obj.responseText );
        }
    } );
}

// Render hourly tab
function write_hourly () {
    jQuery.ajax( { type: "POST", url: 'data_hourly.php',
        dataType: 'json', data : {
            start_month  : start_date().m, start_day  : start_date().d,
            start_year   : start_date().y, start_hour : start_time().h,
            start_minute : start_time().m, end_month  : end_date().m,
            end_day      : end_date().d,   end_year   : end_date().y,
            end_hour     : end_time().h,   end_minute : end_time().m
        }, error: function ( obj ) {
            $("#hourly-tab").html( obj.responseText );
        }
    } );
}

// Render daily tab
function write_daily () {
    jQuery.ajax( { type: "POST", url: 'data_daily.php',
        dataType: 'json', data : {
            start_month  : start_date().m, start_day  : start_date().d,
            start_year   : start_date().y, start_hour : start_time().h,
            start_minute : start_time().m, end_month  : end_date().m,
            end_day      : end_date().d,   end_year   : end_date().y,
            end_hour     : end_time().h,   end_minute : end_time().m
        }, error: function ( obj ) {
            $("#daily-tab").html( obj.responseText );
        }
    } );
}

// Render weekly tab
function write_weekly () {
    jQuery.ajax( { type: "POST", url: 'data_weekly.php',
        dataType: 'json', data : {
            start_month  : start_date().m, start_day  : start_date().d,
            start_year   : start_date().y, start_hour : start_time().h,
            start_minute : start_time().m, end_month  : end_date().m,
            end_day      : end_date().d,   end_year   : end_date().y,
            end_hour     : end_time().h,   end_minute : end_time().m
        }, error: function ( obj ) {
            $("#weekly-tab").html( obj.responseText );
        }
    } );
}

// Render monthly tab
function write_monthly () {
    jQuery.ajax( { type: "POST", url: 'data_monthly.php',
        dataType: 'json', data : {
            start_month  : start_date().m, start_day  : start_date().d,
            start_year   : start_date().y, start_hour : start_time().h,
            start_minute : start_time().m, end_month  : end_date().m,
            end_day      : end_date().d,   end_year   : end_date().y,
            end_hour     : end_time().h,   end_minute : end_time().m
        }, error: function( obj ) {  $("#monthly-tab").html( obj.responseText ) }
    } );
}

// Render yearly tab
function write_yearly () {
    jQuery.ajax( { type: "POST", url: 'data_yearly.php',
        dataType: 'json', data : {
            start_month: start_date().m, start_day: start_date().d,
            start_year: start_date().y, start_hour: start_time().h,
            start_minute: start_time().m, end_month: end_date().m,
            end_day: end_date().d, end_year: end_date().y,
            end_hour: end_time().h, end_minute: end_time().m
        },  error: function( obj ) {  $("#yearly-tab").html( obj.responseText ) }
    } );
}


// Rerender all tabs and charts
function refresh_views() {

        clear_charts();

        write_admin();
        write_total();
        write_hourly();
        write_daily();
        write_weekly();
        write_monthly();
        write_yearly();

        render_charts();
    }



