#include "GPS.h"


GPS::GPS(TinyGPSPlus *gpsObject, HardwareSerial *serial) {
  
  gpsSerial = serial;
  gpsSerial->begin(9600);
  
  if (!EEPROM.begin(1000)) {
    Serial.println("Failed to initialise EEPROM");
    Serial.println("Restarting...");
    delay(1000);
    
    ESP.restart();
  }

  neoGPS = gpsObject;

  

  getSavedGPSParams(&gpsParams);
  
}

void GPS::getSavedGPSParams(GPSParams *gps){
  gps->lat = EEPROM.readFloat(LATITUDE_ADDRESS);
  delay(5);
  gps->lng = EEPROM.readFloat(LONGITUDE_ADDRESS);
  delay(5);
  
  Serial.println("----------------");
  Serial.print("Saved Latitude: ");
  Serial.println(String(gps->lat, 6));
  Serial.print("Saved Longitude: ");
  Serial.println(String(gps->lng, 6));
  Serial.println("----------------");
}

void GPS::saveGPSParams(GPSParams *gps){
   Serial.println("SAVING TO EEPROM");
   EEPROM.writeFloat(LATITUDE_ADDRESS, gps->lat);
   delay(5);
   EEPROM.writeFloat(LONGITUDE_ADDRESS, gps->lng);
   delay(5);

   EEPROM.commit();
}

bool GPS::checkGPSUpdates(GPSParams *gps){
  while (gpsSerial->available() > 0){
    if (neoGPS->encode(gpsSerial->read())){
      if (neoGPS->location.isValid()){
        gps->lat = neoGPS->location.lat();
        gps->lng = neoGPS->location.lng();
        return true;    
      }
      else
      {
        return false;
      }
    }
  }
}

  
