
#include <TinyGsmClient.h>
#include <Arduino.h>


class Modem{
  public:

    //Modem() { // Constructor
    //    ServerRequestRecieved = new char[2]; // Allocate space for 'N' and the null terminator
    //    strcpy(ServerRequestRecieved, "N"); // Set initial value
    //}
    void GetPreferences();
    void GetSignal();
    void NetworkCheck();
    void CheckReg();
    void SetCertificate();
    void GPSSetup();
    void BeginModemAndAT();
    void TestSimStatus();
    bool mqtt_connect();
    static void mqtt_callback(const char *topic, const uint8_t *payload, uint32_t len);
    bool Is_mqtt_connected();
    bool MqttPublish(String payload);
    bool PowerOff();
    void Handle();
    String GetTrackerID();
    static char* GetServerRequest() { return ServerRequestRecieved; } 
    void SetServerRequestN();
    void SetPTR(unsigned int* sleepAmountPtr, unsigned int* alertRadiusPtr, String* contactNumberPtr, double* LatHome, double* LngHome, bool* HomeSet);
    void SetServerRequestY();


    bool DeterminAlert(double lat, double lng);
    
  private: 
    static char* ServerRequestRecieved;

};