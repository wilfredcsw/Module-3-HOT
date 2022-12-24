# -*- coding: utf-8 -*-
"""
Created on Tue Mar  1 19:42:32 2022

@author: user
"""


import json
import mysql.connector
import paho.mqtt.client as mqtt
import requests
import pusher

webhook_url = 'https://discord.com/api/webhooks/1047195620005384252/lmEMLl-jdfpOU2Hk51MDjQVInjlB6b58NlsY_qpurXr3WuBQKQ-8P_0ThUngPB1Y4giZ'

pusher_client = pusher.Pusher(app_id='1', key=u'umpfkpusher', secret=u'u%M15z2h%3A', cluster=u'mt1', ssl=False, host=u'10.26.30.32', port=6001)
# MQTT Settings 
# MQTT_Broker = "test.mosquitto.org"
MQTT_Broker = "10.26.30.33"
MQTT_Port = 1883
Keep_Alive_Interval = 45
MQTT_Topic = "cb20028/water_level"


mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="earlyflood"
)

mycursor = mydb.cursor()


# Function to save Humidity to DB Table
def goToDB(jsonData):

    Value = float(json.loads(jsonData))
    print(Value)

    if (Value > 3):
        try:
            pusher_client.trigger(u'DeviceAlarm', u'App\Events\Alerts', {u'deviceID': 'Water Level'})
        except:
            print("An exception occurred")
        
        # Set the message to send
        message = 'Water is begining to overflow. Currently passed point 3!'

        # Set the payload with the message
        payload = {'content': message}

        # Send the POST request to the webhook URL
        response = requests.post(webhook_url, json=payload)
        
        # TOKEN = "5654699698:AAEJ1FD-f7buTzvCCrBc-Khssp0abzQTCoI"
        # chat_id = "71506801"
        # message = "System Alert : Oxygen Tank Abnormal"
        # url = f"https://api.telegram.org/bot{TOKEN}/sendMessage?chat_id={chat_id}&text={message}"
        # requests.get(url).json()
        sql = "INSERT INTO alert (id,deviceID) VALUES (%s,%s)"
        val = ("",'value')
        mycursor.execute(sql, val)
        mydb.commit()
        print('Alarm trigger')
    else:
        print('No Alarm')
    #     sql = "INSERT INTO o2meter (id,Value) VALUES (%s,%s)"
    #     val = ('',Value)
    #     mycursor.execute(sql, val)
    #     mydb.commit()
    #     print("Send data")
    
    # sql = "INSERT INTO potentiometer (id,Value) VALUES (%s,%s)"
    # val = ('',Value)

    # mycursor.execute(sql, val)
    # mydb.commit()
    # print("Send data")
    
#===============================================================
# Master Function to Select DB Funtion based on MQTT Topic

def sensor_Data_Handler(Topic, jsonData):
    if Topic == MQTT_Topic:
        goToDB(jsonData)
        



#===============================================================
#Subscribe to all Sensors at Base Topic
def on_connect(mosq, obj,flag, rc):
    mqttc.subscribe(MQTT_Topic, 0)

#Save Data into DB Table
def on_message(mosq, obj, msg):
    print ("MQTT Data Received...")
    # print ("MQTT Topic: " + msg.topic)  
    # print ("Data: " + str(msg.payload))
    sensor_Data_Handler(msg.topic, msg.payload)

def on_subscribe(mosq, obj, mid, granted_qos):
    pass


mqttc = mqtt.Client()
# Assign event callbacks
mqttc.username_pw_set('umpfkpusher', "fkump@2022")
mqttc.on_message = on_message
mqttc.on_connect = on_connect
mqttc.on_subscribe = on_subscribe

# Connect

mqttc.connect(MQTT_Broker, int(MQTT_Port), int(Keep_Alive_Interval))
# Continue the network loop
mqttc.loop_forever()

#===============================================================
