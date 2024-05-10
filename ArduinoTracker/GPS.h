#include <Arduino.h> //For String

class GPS{

  public:
    void getGPS(String &time, String &lat, String &lng, String &course, uint32_t &GpsCount);
    void smartDelay(unsigned long ms);
    double haversineDist(double lat, double lng, double LatHome, double LngHome);
  private: 






};