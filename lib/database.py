
# Database,py
# Handles data submisssions into the database

# - - - Imports - - -

import requests

from datetime import datetime


# - - - Database Class - - -
# Handles data submisssions into the database

class Database:

    # Constructor
    # Records the url of the database access point for future use
    def __init__ ( self, endpoint ):

        self.endpoint = endpoint


    # Directly submits provided data into the database
    def submit( self, day, hour, week, month, year, minute = 0, seconds = 0 ):
        print("entered!")

        r = requests.post(
            self.endpoint,
            data = {
                'date' : '%s-%s-%s' %( str(year).zfill(2), str(month).zfill(2), str(day).zfill(2) ),
                'time' : '%s:%s:%s' %( str(hour).zfill(2), str(minute).zfill(2), str(seconds).zfill(2) )
            }
        )
        print("exit")

        #return (0)
        return ( r.status_code, r.reason, r.text )


    # Submit a datetime object.
    # Lowers call to Database.submit( )
    def submit_datetime( self, date ):

        days_from_jan_1       = ( date - datetime(1,1,1) ).days
        year_start_adjustment = ( date.weekday() + 1 ) % 7

        week = int( ( days_from_jan_1 + year_start_adjustment ) / 7 )

        return self.submit( date.day, date.hour, week, date.month, date.year, date.minute, date.second )


    # Calculates and submits the current time
    # Lowers call to Database.submit_datetime
    def submit_now( self ):

        now = datetime.now()

        return self.submit_datetime( now )


