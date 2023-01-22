// LED.h
#ifndef GPS_h
#define GPS_h

#include <EEPROM.h>
#include <TinyGPSPlus.h>
#include <HardwareSerial.h>
#include <Arduino.h>

// EEPROM Address Map
#define LATITUDE_ADDRESS  50
#define LONGITUDE_ADDRESS 100


struct GPSParams{
  float lat;
  float lng;
 
  // will add other params soon like speed, altitude, etc
};

class GPS{
  
  private:
    HardwareSerial *gpsSerial;
    GPSParams gpsParams;
    TinyGPSPlus *neoGPS;

  public:
    GPS(TinyGPSPlus *gpsObject, HardwareSerial *serial);
    void init();
    void getSavedGPSParams(GPSParams *gps);
    void saveGPSParams(GPSParams *gps);
    bool checkGPSUpdates(GPSParams *gps);
    
};

#endif
