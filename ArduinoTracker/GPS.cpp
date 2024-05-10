#include "utilities.h"
#include "GPS.h"
#include <Arduino.h>
#include <TinyGPS++.h>
TinyGPSPlus gps;

void GPS::getGPS(String &time, String &lat, String &lng, String &course, uint32_t &GpsCount) {
    Serial.println("Encoding GPS ");

    if(gps.time.isValid()){ //check if time is valid
      time = String(gps.time.hour()) + ":" + String(gps.time.minute()) + ":" + String(gps.time.second()); // Concat hours, minutes and seconds

    }else{
      time = "invalid";
    }
    if(gps.location.isValid()){ //Check if location is valid
        lat = String(gps.location.lat(), 7); //Save lat/lng to 7 decimanl places
        Serial.print("Lat: ");
        Serial.println(gps.location.lat(), 7);

        lng = String(gps.location.lng(), 7);

    }else{
        lat = "Invalid";
        lng = "Invalid";

    }

    if(gps.course.isValid()){

      course = String(gps.course.deg());
    }else{
      course = "Invalid";
    }
    if(gps.satellites.isValid()){
          GpsCount = gps.satellites.value();
    } else{
          GpsCount = 0;
    }
  
}




void GPS::smartDelay(unsigned long ms) //Acts as Delay() but allows gps to be encoded
{
    int ch = 0;
    unsigned long start = millis();
    do {
        while (SerialAT.available()) {
            ch = SerialAT.read();
            // Serial.write(ch);
            gps.encode(ch);
        }
    } while (millis() - start < ms);
}



double GPS::haversineDist(double lat, double lng, double LatHome, double LngHome) //Lat and Lng is current cords
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




