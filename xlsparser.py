import pandas as pd
import numpy as np
import mysql.connector
import datetime

hostname = "database.cse.tamu.edu"
username = "shotaehrlich"
password = "mmDS844825"
dbName = "shotaehrlich"

database = mysql.connector.connect(host=hostname,user=username,passwd=password,db=dbName)
cursor = database.cursor(buffered=True)

def insert_sql_person_in(dateIn,timeIn):
    sql = "INSERT INTO `" + dbName + "`.`test`(`counter`,`date`,`time`) VALUES (NULL,'" + str(dateIn) + "','" + str(timeIn) +  "')"
    try:
        print("Inserted: " + str(dateIn) + " " + str(timeIn))
        cursor.execute(sql)
        database.commit()
    except:
        print("Unable to execute" + sql)



file = 'ParkingData.xlsx'
xl = pd.ExcelFile(file)
#print(xl.sheet_names)
df1 = xl.parse('Sheet1')
for x in range(0,4435):
    value = np.asscalar(df1.iloc[x][0])
    if(value):
        dateti = df1.iloc[x][1].split(" ")
        thedate = datetime.datetime.strptime(dateti[0],"%m/%d/%Y").strftime("%Y-%m-%d")
        #print(thedate)
        thetime = dateti[1]
        insert_sql_person_in(thedate,thetime)

database.close()
