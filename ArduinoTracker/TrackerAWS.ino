
#define TINY_GSM_RX_BUFFER          1024 // Set RX buffer to 1Kb

// See all AT commands, if wanted
#define DUMP_AT_COMMANDS

#include "utilities.h"
#include <ArduinoJson.h>
#include <Preferences.h>
#include "Util.h" //Includes Utility methods
#include "Modem.h"
#include "GPS.h"
//Class initalisation
Util UtiltyMethods; 
GPS GPSMethods;
Modem ModemMethods;



uint32_t lastReconnectAttempt = 0;
//Ticker reconnectTicker; // Create a Ticker object
uint32_t check_connect_millis = 0;
//TinyGsmClient client(modem);
//PubSubClient  mqtt(client);

uint32_t G = 0;

//uint32_t check_connect_millis = 0;
uint32_t Failed_Attempt = 0;

unsigned long millisSinceLastCheck = 0;
//Preferance variables - Non Volatile Memory

unsigned int SleepAmmount;
unsigned int AlertRadius;
String ContactNumber;
double LatHome;
double LngHome;
bool HomeSet;




String DeviceUniqueIdentifier;
bool DeviceSetUp;





void setup()
{

    Serial.begin(115200); // Set console baud rate


    ModemMethods.SetPTR(&SleepAmmount, &AlertRadius, &ContactNumber, &LatHome, &LngHome, &HomeSet); //Sets memory address of variables

    ModemMethods.GetPreferences();


    Serial.println("Start Sketch");
    SerialAT.begin(115200, SERIAL_8N1, MODEM_RX_PIN, MODEM_TX_PIN);
    //Preferences preferences; //Preferances intalised here as not to interfere with Modem.cpp preferences
    //UtiltyMethods.RechargeState(); //Stays in a Recharge Loop if recharging, disabling all features


    //ModemMethods = Modem(AmserOCwsg);

    ModemMethods.BeginModemAndAT();



    
    Serial.println("######################################");
    Serial.println(SleepAmmount);
    Serial.println("######################################");

    ModemMethods.GPSSetup(); //Initalizes GPS

    Serial.print("Battery is: " + String(UtiltyMethods.GetBattery())); // Shorter prefix
    Serial.println("##############DELAY");
    delay(20000);

    ModemMethods.TestSimStatus();

    ModemMethods.CheckReg(); //Checks Registration

    ModemMethods.SetCertificate(); //Sets Certificates and initalizes MQTT


    // Connecting to AWS IOT Core
    if (!ModemMethods.mqtt_connect()) {

        Serial.println("mqtt Did not connect");

        return;
    }





}

void loop()
{


  String time, lat, lng, course; // Declare individual String variables
  bool mqttPublishNeeded = false;
  JsonDocument GPSPay;  
  uint32_t GpsCount = 0;
  String payload; 
  if (millis() - millisSinceLastCheck >= (SleepAmmount / 2)) {
    
    GPSMethods.getGPS(time, lat, lng, course, GpsCount);
    bool Alert = false;
    if(lat != "Invalid"){
      Alert = ModemMethods.DeterminAlert(lat.toDouble(), lng.toDouble());
    
    }

    GPSPay["ID"] = ModemMethods.GetTrackerID();
    GPSPay["Time"] = time;
    GPSPay["Lat"] = lat;
    GPSPay["Lng"] = lng;
    GPSPay["Course"] = course;
    GPSPay["GpsCount"] = GpsCount;
    GPSPay["battery_voltage"] = UtiltyMethods.GetBattery();
    GPSPay["ServerRequestRecieved"] = String(ModemMethods.GetServerRequest());
    GPSPay["Alert"] = Alert;


    serializeJson(GPSPay, payload); 
    Serial.println(payload);
    millisSinceLastCheck = millis(); 
    mqttPublishNeeded = true;
  }



  if(mqttPublishNeeded){
    if (!ModemMethods.Is_mqtt_connected()) { 
    Serial.println("MQTT Disconnected");
    ModemMethods.NetworkCheck(); //Checks and Re-establishes network connection
    Failed_Attempt++;
    }else{
      if (!ModemMethods.MqttPublish(payload)) { //Attempts to publish
        Serial.println("MQTT FAILED");
        Failed_Attempt++;
        ModemMethods.GetSignal();
      }else{//Publish success
        Serial.println("Publish Success");
        mqttPublishNeeded = false;
        Failed_Attempt = 0;
        Serial.println(SleepAmmount);
        Serial.println(AlertRadius);
        Serial.println(ContactNumber);
        ModemMethods.SetServerRequestN();
        unsigned long ResponseCheckMillies = millis();
        while (millis() - ResponseCheckMillies < 5000) { // Loop runs to detect topic published to Subscription
          GPSMethods.smartDelay(10);//Smart Delay to allow time for MQTT callback after publish
          ModemMethods.Handle(); //Does something important
        }
        
        UtiltyMethods.InitaliseSleep(SleepAmmount); //Light sleep only initalised if publish succesfull
        
      }
    }
    if(Failed_Attempt >= 5){ //Restart board if failing to retrieve connection
      Serial.println("Restart Inbound");
      delay(1000);
      if(ModemMethods.PowerOff()){
          Serial.println("Modem Power Off");
          delay(2000);
          ESP.restart();
      }
    }
    

  }
  ModemMethods.Handle(); //Does something important
  GPSMethods.smartDelay(100); //Encodes GPS 


  
}




/*


static double haversineDist(double lat, double lng) //Lat and Lng is current cords
    {

        double LatSet = 51.481805; //Testing location
        double LonSet = -3.181773;
        // distance between latitudes
        // and longitudes
        double dLat = (LatSet - lat) *
                      M_PI / 180.0;
        double dLon = (LonSet - lng) * 
                      M_PI / 180.0;
 
        // convert to radians
        LatSet = (LatSet) * M_PI / 180.0;
        lat = (lat) * M_PI / 180.0;
 
        // apply formulae
        double a = pow(sin(dLat / 2), 2) + 
                   pow(sin(dLon / 2), 2) * 
                   cos(LatSet) * cos(lat);
        double rad = 6371;
        double c = 2 * asin(sqrt(a));
        return (rad * c) * 100;


    }


static double DeterminTheft(double lat, double lng){

  if(haversineDist(lat, lng) < AlertRadius){
    if(ContactNumber != "0"){
      modem.sendSMS(ContactNumber, String("Please check Tracker Dashboard"));
    }


  }

}


*/



