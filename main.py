
# Main.py
# Main Loop and intitializations for running project

DATABASE_ENDPOINT = "http://projects.cse.tamu.edu/shotaehrlich/passingPythonInfo.php" # Enter url to database endpoint here !!!!


# - - - Imports - - -

from lib.arduino          import Arduino
from lib.database         import Database
from lib.charecterization import Charecterization


# - - - Initialize Objects - - -

arduino          = Arduino ({ 'port' : '/dev/ttyACM0' } ) # Change to proper port from Arduino IDE
database         = Database         ( DATABASE_ENDPOINT )
charecterization = Charecterization ( 7, False )

# - - - Main Loop - - -

while True:

    # Poll the arduino for new data
    #print("hello")
    r = arduino.poll()
    #print("1hello")

    # Plot and analyse the data for enterance and exit events
    charecterization.record_raw( r )
    #print(charecterization.enter)

    # Handles enterance events
    if( charecterization.enter ):

        #print( "Enter" )
        database.submit_now(  )
        #print("submitted!!")
