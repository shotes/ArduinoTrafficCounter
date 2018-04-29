
#  arduino.py
# Handles communication with arduino

# - - - Imports - - -

import serial


# --- --- --- Arduino Class --- --- ---
# Handles communications with the walking counter

class Arduino:

    # Default configuration for walking counter device
    default_configuration = {
        'port'        : '/dev/ttyACM0',
        'baud'        : 57600,
    }

    # Constructor
    # Stores configuration
    def __init__ ( self, configuration = { } ):

    	# Merge provided configuration with default configuration
        self.configuration = Arduino.default_configuration
        self.configuration.update( configuration )

        # Create serial connection
        self.serial = serial.Serial( self.configuration['port'], self.configuration['baud'] )

while(True):
    if(serial.isAvailable()):

