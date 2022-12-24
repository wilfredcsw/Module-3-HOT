import paho.mqtt.client as mqtt
from random import randrange, uniform
import time
import pandas as pd
import numpy as np
import json

port = 1883
mqttBroker = "10.26.30.33"
client = mqtt.Client("cb20028")
client.username_pw_set("umpfk", "u4h%w1Tr12")
client.connect(mqttBroker,port)

data= pd.read_csv("flooddataset.csv")
# print(data)
uall=data.to_numpy()
# uall = np.flipud(uall)
# print(uall)
p,q = uall.shape
# uall = np.float32(uall)
ufinal = uall


while True:
    for x in ufinal:
        try:
            MQTT_MSG1 = str(x[0])
            MQTT_MSG2 = str(x[1])
            MQTT_MSG3 = str(x[2])
    
            
            client.publish("cb20028/temperature_data",MQTT_MSG1,0)
            client.publish("cb20028/humidity_data",MQTT_MSG2,0)
            client.publish("cb20028/water_level",MQTT_MSG3,0)
            print('data sent')
        except:
            print('try')
        time.sleep(1)
        
            