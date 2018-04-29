
#  charecterization.py
# Holds the charecterization module

# - - - Imports - - -

import matplotlib.pyplot as plt

from math import e

# - - - Charecterization Class - - -
# Used to analyze data from ultrasonic sensor to determine when someone walks past the sensor

class Charecterization:

    # Constructor
    # Initializes with empty data
    # Assumes an initialy static input function
    # l is the strength of the hysteresis in the filter
    # the graph parameter specifies whether or not we display a graph for debugging purposes
    def __init__( self, l = 7, graph = False ):

        # Whether an enterance or exit event is currently detected
        self.enter = False
        self.exit  = False

        # Strength of filter hysteresis
        self.l = l

        # Raw data
        self.r  = [ 0 for _ in range( self.l ) ]

        # Filtered Data
        self.x  = [ 0 for _ in range( self.l ) ]

        # First order derivative
        self.d  = [ 0 for _ in range( self.l ) ]

        # Second order derivative
        self.d2 = [ 0 for _ in range( self.l ) ]

        # Third order derivative
        self.d3 = [ 0 for _ in range( self.l ) ]

        # Lock to prevent double counting
        self.lock = 0

        # Whether or not to display a graph for debugging data
        self.graph = graph

        # Amount of datapoints processed
        self.i = 0


    # Records new raw data, filters it, and calculates derivative
    # Calls analyse() to detect enterance and exit events
    def record_raw( self, r ):

        # Shift data left
        for j in range(self.l-1):
            self.r[j]  = self.r[j+1]
            self.x[j]  = self.x[j+1]
            self.d[j]  = self.d[j+1]
            self.d2[j] = self.d2[j+1]
            self.d3[j] = self.d3[j+1]

        # Record raw data
        self.r[self.l-1] = r

        # Calculate filtered value as a roughly gaussian weighted sliding average
        self.x[self.l-1] = self.mean( self.r, self.l )

        # Calculate first derivative of filtered data
        if( self.i >= 1 ):
            self.d[self.l-1] = self.r[self.l-1]  - self.r[self.l-2]

        # Calculate second derivative of filtered data
        if( self.i >= 2 ):
            self.d2[self.l-1] = self.d[self.l-1]  - self.d[self.l-2]

        # Calculate third derivative of filtered data
        if( self.i >= 3 ):
            self.d3[self.l-1] = self.d2[self.l-1] - self.d2[self.l-2]

        # Update graph if enabled
        if( self.graph ):

            # Plots filtered data in blue
            plt.scatter(self.i, self.x[self.l-1], color='b' )

            # Plots first derivative in red
            plt.scatter(self.i, self.d[self.l-1], color='r' )

            # Maintains graph interactivity
            plt.pause(0.0000001)

        # increment counter
        self.i = self.i +1

        # Analyse data for enter and exit events
        self.analyse()


    # Analyses data to calculate enter and exit events
    def analyse( self, pickyness = .66 ):

        # Check if there is a lock from a recent detection to account for fritzing
        if( self.lock > 0 ):

            self.lock  = self.lock - 1
            self.enter = self.exit  = False

        # Check for enterance events
        elif len( filter( lambda x : x < -0.05, self.d ) ) < pickyness * self.l:

            self.enter = True
            self.exit  = False

            # Lock event detection
            self.lock = self.l

        # Check for exit events
        elif len( filter( lambda x : x > 0.05, self.d ) ) < pickyness * self.l:

            self.enter = False
            self.exit  = True

            # Lock event detection
            self.lock = self.l

        else:

            self.enter = self.exit = False

    # ~ Gaussian Sliding average filter
    # The kernel parameter is the sliding kernel (array holding the weights)
    def mean( self, x, l, kernel = lambda i : 2**i ):

        return sum( [kernel(i)*x[i] for i in range(l)] ) / sum( [ kernel(i) for i in range(l) ] )

    # Median filter
    # l is the effective size of the array
    # k is the amount of values to be returned
    def median( self, x, l, k ):

        return sorted(x)[ (l-k)/2 : (k-l)/2 ]



