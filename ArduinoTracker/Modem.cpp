#include "utilities.h"
#include "Modem.h"
#include <Arduino.h>
#include <TinyGsmClient.h>
#include <PubSubClient.h>
#include <Preferences.h>
#include <ArduinoJson.h>
#include "GPS.h"


#include "certs/AWSClientCertificate.h"
#include "certs/AWSClientPrivateKey.h"
#include "certs/AmazonRootCA.h"



Preferences preferences;
GPS GPSDetect;

#ifdef DUMP_AT_COMMANDS  // if enabled it requires the streamDebugger lib
#include <StreamDebugger.h>
StreamDebugger debugger(SerialAT, Serial);
TinyGsm modem(debugger);
#else
TinyGsm modem(SerialAT);
#endif

#define SerialGPS Serial2



// MQTT details
const char *broker = "a2sn3ffcqspsim-ats.iot.eu-west-2.amazonaws.com";
const uint16_t broker_port = 8883;
const char *clien_id = "LILGYGOTRACKER";

String TrackerID = "BTAAA001-A1A";
// Replace the topic you want to subscribe to
const char *subscribe_topic = "Tracker/sub";
// Replace the theme you want to publish
const char *publish_topic = "Tracker/pub";
// Current connection index, range 0~1
const uint8_t mqtt_client_id = 0;
//char* ServerRequestRecieved = "N";
int AmserOCwsg;

//Pointers for Variables in TrackerAWS.INO
unsigned int* SleepAmmountPtr;
unsigned int* AlertRadiusPtr;
String* ContactNumberPtr;
double* LatHomePtr;
double* LngHomePtr;
bool* HomeSetPtr;

char* Modem::ServerRequestRecieved = "N";
//char* Modem::ServerRequestRecieved = new char[2]; 
//strcpy(Modem::ServerRequestRecieved, "N"); // Initial value

void Modem::GetPreferences(){

  preferences.begin("Settings", false);

  *SleepAmmountPtr = preferences.getUInt("SleepAmmount", 20); //If not set, default will be 20
  *AlertRadiusPtr = preferences.getUInt("AlertRadius", 500); //500 Meters alert radius
  *ContactNumberPtr = preferences.putString("ContactNumber", "0"); //500 Meters alert radius
  *LatHomePtr = preferences.getDouble("LatHome", 0); //500 Meters alert radius
  *LngHomePtr = preferences.getDouble("LngHome", 0); //500 Meters alert radius
  *HomeSetPtr = preferences.getBool("HomeSet", false); //Has the home been set?


  preferences.end();

}

void Modem::GetSignal(){
        int networkStatus = modem.getRegistrationStatus();
        int signalQuality = modem.getSignalQuality();

        Serial.print("Network Status: ");
        Serial.println(networkStatus); 

        Serial.print("Signal Quality: ");
        Serial.println(signalQuality); 
}



void Modem::NetworkCheck(){


          if (!modem.isNetworkConnected()) {


          Serial.println("Network disconnected");

          if (!modem.waitForNetwork(180000L, true)) {
            Serial.println(" fail");
            delay(10000);
            return;
          }

          if (modem.isNetworkConnected()) {
            Serial.println("Network re-connected");
          }
      }
}


void Modem::CheckReg(){



    Serial.println("Checking Registration");

    // Check network registration status and network signal status
    int16_t sq ;
    Serial.print("Wait for the modem to register with the network.");
    RegStatus status = REG_NO_RESULT;
    while (status == REG_NO_RESULT || status == REG_SEARCHING || status == REG_UNREGISTERED) {
        status = modem.getRegistrationStatus();
        switch (status) {
        case REG_UNREGISTERED:
        case REG_SEARCHING:
            sq = modem.getSignalQuality();
            Serial.printf("[%lu] Signal Quality:%d", millis() / 1000, sq);
            delay(1000);
            break;
        case REG_DENIED:
            Serial.println("Network registration was rejected, please check if the APN is correct");
            return ;
        case REG_OK_HOME:
            Serial.println("Online registration successful");
            break;
        case REG_OK_ROAMING:
            Serial.println("Network registration successful, currently in roaming mode");
            break;
        default:
            Serial.printf("Registration Status:%d\n", status);
            delay(1000);
            break;
        }
    }
    Serial.println();


    Serial.printf("Registration Status:%d\n", status);
    delay(1000);

    String ueInfo;
    if (modem.getSystemInformation(ueInfo)) {
        Serial.print("Inquiring UE system information:");
        Serial.println(ueInfo);
    }

    if (!modem.enableNetwork()) {
        Serial.println("Enable network failed!");
    }

    delay(1000);
}


void Modem::SetCertificate(){

    String ipAddress = modem.getLocalIP();
    Serial.print("Network IP:"); Serial.println(ipAddress);

    // Initialize MQTT, use SSL
    modem.mqtt_begin(true);

    // Set Amazon Certificate
    modem.mqtt_set_certificate(AmazonRootCA, AWSClientCertificate, AWSClientPrivateKey);

}

void Modem::GPSSetup(){

    Serial.println("Enabling GPS/GNSS/GLONASS");
    while (!modem.enableGPS(MODEM_GPS_ENABLE_GPIO)) {
        Serial.print(".");
    }
    Serial.println();
    Serial.println("GPS Enabled");
    modem.setGPSBaud(115200);

    modem.setGPSMode(3);    //GPS + BD

    modem.configNMEASentence(1, 1, 1, 1, 1, 1);

    modem.setGPSOutputRate(1);

    modem.enableNMEA();
}

void Modem::BeginModemAndAT(){

  #ifdef BOARD_POWERON_PIN
      pinMode(BOARD_POWERON_PIN, OUTPUT);
      digitalWrite(BOARD_POWERON_PIN, HIGH);
  #endif

    // Set modem reset pin ,reset modem
    pinMode(MODEM_RESET_PIN, OUTPUT);
    digitalWrite(MODEM_RESET_PIN, !MODEM_RESET_LEVEL); delay(100);
    digitalWrite(MODEM_RESET_PIN, MODEM_RESET_LEVEL); delay(2600);
    digitalWrite(MODEM_RESET_PIN, !MODEM_RESET_LEVEL);

    pinMode(BOARD_PWRKEY_PIN, OUTPUT);
    digitalWrite(BOARD_PWRKEY_PIN, LOW);
    delay(100);
    digitalWrite(BOARD_PWRKEY_PIN, HIGH);
    delay(100);
    digitalWrite(BOARD_PWRKEY_PIN, LOW);

    // Check if the modem is online
    Serial.println("Start modem...");
    int retry = 0;
    while (!modem.testAT(1000)) {
        Serial.println(".");
        if (retry++ > 10) {
            digitalWrite(BOARD_PWRKEY_PIN, LOW);
            delay(100);
            digitalWrite(BOARD_PWRKEY_PIN, HIGH);
            delay(1000);
            digitalWrite(BOARD_PWRKEY_PIN, LOW);
            retry = 0;
        }
    }
    Serial.println();
    delay(200);
}


void Modem::TestSimStatus(){

  // Check if SIM card is online
  SimStatus sim = SIM_ERROR;
  while (sim != SIM_READY) {
      sim = modem.getSimStatus();
      switch (sim) {
      case SIM_READY:
          Serial.println("SIM card online");
          break;
      case SIM_LOCKED:
          Serial.println("The SIM card is locked. Please unlock the SIM card first.");
          // const char *SIMCARD_PIN_CODE = "123456";
          // modem.simUnlock(SIMCARD_PIN_CODE);
          break;
      default:
          break;
      }
      delay(1000);
  }

}


bool Modem::mqtt_connect()
{
    Serial.print("Connecting to ");
    Serial.print(broker);
    Serial.print("######");

    Serial.print("Username is:  ");
    Serial.println(clien_id);

    Serial.print("mqtt_client_id:  ");
    Serial.println(mqtt_client_id);
    bool ret = modem.mqtt_connect(mqtt_client_id, broker, broker_port, clien_id);
    if (!ret) {
        Serial.println("Failed!"); return false;
    }
    Serial.println("successed.");

    if (modem.mqtt_connected()) {
        Serial.println("MQTT has connected!");
    } else {
        return false;
    }
    // Set MQTT processing callback

    modem.mqtt_set_callback(mqtt_callback);


    // Subscribe to topic

    modem.mqtt_subscribe(mqtt_client_id, subscribe_topic);

    return true;
}



void Modem::mqtt_callback(const char *topic, const uint8_t *payload, uint32_t len)
{
    ServerRequestRecieved = "Y";
    //SetServerRequestY();
    preferences.begin("Settings", false);
    Serial.println();
    Serial.println("======mqtt_callback======");
    Serial.print("Topic:"); Serial.println(topic);
    //Serial.print("ServerRequest: "); 
    //Serial.println(ServerRequestRecieved); 

    Serial.println("Payload:"); 

    const size_t capacity = JSON_OBJECT_SIZE(4) + 100; 
    DynamicJsonDocument doc(capacity);

    // Deserialize JSON
    DeserializationError error = deserializeJson(doc, payload, len);
    if (error) {
        Serial.print(F("deserializeJson() failed: "));
        Serial.println(error.f_str());
        return;
      }

    // Access key-value pairs
    const char* message = doc["SleepTime"]; // Get the value of the "message" key

    if(doc["SleepTime"]){
      preferences.putUInt("SleepAmmount", doc["SleepTime"]);
      AmserOCwsg = doc["SleepTime"];
      *SleepAmmountPtr = doc["SleepTime"].as<unsigned int>();
    }
    if(doc["AlertRadius"]){
      preferences.putUInt("AlertRadius", doc["AlertRadius"]);
      *AlertRadiusPtr = doc["AlertRadius"].as<unsigned int>();
    }
    if(doc["ContactNumber"]){
      preferences.putUInt("ContactNumber", doc["ContactNumber"]);
      *ContactNumberPtr = doc["ContactNumber"].as<String>();
    }
    if(doc["LatHome"] && doc["LngHome"]){
      preferences.putDouble("LatHome", doc["LatHome"]);
      preferences.putDouble("LngHome", doc["LngHome"]);
      preferences.getBool("HomeSet", true);

      *LatHomePtr = doc["LatHome"].as<double>();
      *LngHomePtr = doc["LngHome"].as<double>();
      *HomeSetPtr = true;
      //*ContactNumberPtr = doc["ContactNumber"].as<String>();
    }

    Serial.print("Sleep Time: ");
    Serial.println(*SleepAmmountPtr);
    Serial.print("AlertRadius: ");
    Serial.println(*AlertRadiusPtr);
    Serial.print("Contact Number: ");
    Serial.println(*ContactNumberPtr);
    Serial.print("LatHomePtr: ");
    Serial.println(*LatHomePtr);
    Serial.print("LngHomePtr: ");
    Serial.println(*LngHomePtr);






    Serial.print("Message: ");
    Serial.println(AmserOCwsg);
    Serial.println();
    Serial.println("=========================");
    Serial.println(preferences.getUInt("SleepAmmount", 0));
    Serial.println("=========================");
    preferences.end();
}

bool Modem::Is_mqtt_connected(){

  if(modem.mqtt_connected()){
    return true;
  }else{
    return false;
  }
}

bool Modem::MqttPublish(String payload){
  if(modem.mqtt_publish(mqtt_client_id, publish_topic, payload.c_str())){
    return true;
  }else{
    return false;
  }

}

bool Modem::PowerOff(){
  if(modem.poweroff()){
    return true;
  }else{
    return false;
  }
}

void Modem::Handle(){modem.mqtt_handle();}

String Modem::GetTrackerID(){return TrackerID;}


void Modem::SetServerRequestN(){

  ServerRequestRecieved = "N";
  Serial.print("Server Set N: ");
  Serial.println(ServerRequestRecieved);
}

void Modem::SetServerRequestY(){
  Serial.print("Server Set Y: ");
  ServerRequestRecieved = "Y";
}

void Modem::SetPTR(unsigned int* sleepAmountPtr, unsigned int* alertRadiusPtr, String* contactNumberPtr, double* LatHome, double* LngHome, bool* HomeSet) {
  SleepAmmountPtr = sleepAmountPtr;
  AlertRadiusPtr = alertRadiusPtr;
  ContactNumberPtr = contactNumberPtr;
  LatHomePtr = LatHome;
  LngHomePtr = LngHome;
  HomeSetPtr = HomeSet;

}

bool Modem::DeterminAlert(double lat, double lng){

  if(*HomeSetPtr && GPSDetect.haversineDist(lat, lng, *LatHomePtr, *LngHomePtr) < *AlertRadiusPtr){
    if(*ContactNumberPtr != "0"){
      modem.sendSMS(*ContactNumberPtr, String("Please check Tracker Dashboard"));
    }
    return true;
  }else{
    return false;
  }

}


