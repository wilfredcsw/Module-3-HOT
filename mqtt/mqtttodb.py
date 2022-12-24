import json
import mysql.connector
import paho.mqtt.client as mqtt

MQTT_Broker = "10.26.30.33"
MQTT_Port = 1883
Keep_Alive_Interval = 45
MQTT_Topic = "cb20028/#"


mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="earlyflood"
)

mycursor = mydb.cursor()


# Function to save temperature to DB Table
def goToDBfloodtemp(jsonData):

    Value = float(json.loads(jsonData))
    # print(Value)
    
    sql = "INSERT INTO temperature_data (id, value) VALUES (%s,%s)"
    val = ('',Value)

    mycursor.execute(sql, val)
    mydb.commit()
    print("Current Data Store")
    
def goToDBfloodhumid(jsonData):

    Value = float(json.loads(jsonData))
    # print(Value)
    
    sql = "INSERT INTO humidity_data (id, value) VALUES (%s,%s)"
    val = ('',Value)

    mycursor.execute(sql, val)
    mydb.commit()
    print("Current Data Store")
    
def goToDBwaterlevel(jsonData):

    Value = float(json.loads(jsonData))
    # print(Value)
    
    sql = "INSERT INTO water_level (id, value) VALUES (%s,%s)"
    val = ('',Value)

    mycursor.execute(sql, val)
    mydb.commit()
    print("Current Data Store")
    
#===============================================================
# Master Function to Select DB Funtion based on MQTT Topic

def sensor_Data_Handler(Topic, jsonData):
    if Topic == "cb20028/temperature_data":
        goToDBfloodtemp(jsonData)
    if Topic == "cb20028/humidity_data":
        goToDBfloodhumid(jsonData)
    if Topic == "cb20028/water_level":
        goToDBwaterlevel(jsonData)
        
        
#===============================================================
#Subscribe to all Sensors at Base Topic
def on_connect(mosq, obj,flag, rc):
    mqttc.subscribe(MQTT_Topic, 0)

#Save Data into DB Table
def on_message(mosq, obj, msg):
    # print ("MQTT Data Received...")
    # print ("MQTT Topic: " + msg.topic)  
    # print ("Data: " + str(msg.payload))
    sensor_Data_Handler(msg.topic, msg.payload)

def on_subscribe(mosq, obj, mid, granted_qos):
    pass


mqttc = mqtt.Client('cb20028db')
# Assign event callbacks
mqttc.username_pw_set("umpfk", "u4h%w1Tr12")
#mqttc.username_pw_set("umpfk", "umpiot123")

mqttc.on_message = on_message
mqttc.on_connect = on_connect
mqttc.on_subscribe = on_subscribe

# Connect

mqttc.connect(MQTT_Broker, int(MQTT_Port), int(Keep_Alive_Interval))
# Continue the network loop
mqttc.loop_forever()

#===============================================================
