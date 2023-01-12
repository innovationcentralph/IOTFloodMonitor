#include <EEPROM.h>
#include <TinyGPSPlus.h>
#include <HardwareSerial.h>

#include "UserConfig.h"
#include "SystemConfig.h"

#include "src/Ultrasonic/src/Ultrasonic.h"
#include "src/HTTPSClient.h"
#include "src/Wifi.h"
#include "src/GPS.h"

// Instance creation
TinyGPSPlus gps;
HardwareSerial sim800l(1);
HardwareSerial gpsSerial(2);
GPS GPS(&gps, &gpsSerial);

GPSParams freshGPSCoordinates;
GPSParams savedGPSCoordinates;

Wifi Wifi("324dBm", "randomPASS");
HTTPSClient HTTPClient;

Ultrasonic UltrasonicLevel(Proximity.Pins.Trigger, Proximity.Pins.Echo);

 
// Global Variables
unsigned long int checkWaterLevelMillis = 0;
unsigned long int datalogMillis = 0;
unsigned long int gpsSavingMillis = 0;
//String adminContactNumber  = "+639161886443"; 
String adminContactNumber  = "+639159243835"; 

void setup() {
  
  Serial.begin(9600);

  pinMode(FloatSwitch.Pins.Critical, INPUT_PULLUP);
  pinMode(FloatSwitch.Pins.Safe, INPUT_PULLUP);

  pinMode(Indicators.Safe, OUTPUT);
  pinMode(Indicators.Warning, OUTPUT);
  pinMode(Indicators.Danger, OUTPUT);

  Wifi.connect();

  GPS.getSavedGPSParams(&savedGPSCoordinates);

  checkWaterLevelMillis = millis();
  datalogMillis = millis();
  gpsSavingMillis = millis();
  
}

void loop() {

  /* Get water level values */
  if(millis() - checkWaterLevelMillis > 500){
    // Get raw water level value 
    int rawLevel = UltrasonicLevel.getProximity();

    // Map raw value reading to percentage
    Proximity.Value = map(rawLevel, Proximity.Threshold.Min,Proximity.Threshold.Max, 100, 0);

    // Limit Reading
    if(Proximity.Value > 100)
      Proximity.Value  = 100;
    if(Proximity.Value < 0);
      Proximity.Value = 0;

    Serial.println("Proximity Percentage: " + String(Proximity.Value));

    /* Check Float Switch States */
    FloatSwitch.criticalLevel = digitalRead(FloatSwitch.Pins.Critical);
    FloatSwitch.safeLevel     = digitalRead(FloatSwitch.Pins.Safe);

    Serial.println("FLOAT SAFE: " + String(FloatSwitch.safeLevel) + "\tFLOAT CRITICAL: " + String(FloatSwitch.criticalLevel));

    // Define safety level
    if(FloatSwitch.safeLevel == NOT_TRIGGERED && FloatSwitch.criticalLevel == NOT_TRIGGERED){
      FloatSwitch.SafetyLevel = LEVEL_SAFE;
      Serial.println("SAFETY LEVEL: SAFE");
    }
    else if(FloatSwitch.safeLevel == TRIGGERED && FloatSwitch.criticalLevel == NOT_TRIGGERED){
      FloatSwitch.SafetyLevel = LEVEL_WARNING;
      Serial.println("SAFETY LEVEL: WARNING");
    }
    else if(FloatSwitch.safeLevel == TRIGGERED && FloatSwitch.criticalLevel == TRIGGERED){
      FloatSwitch.SafetyLevel = LEVEL_DANGER;
      Serial.println("SAFETY LEVEL: DANGER");
    }
    else{
      FloatSwitch.SafetyLevel = LEVEL_ERROR;
      Serial.println("SAFETY LEVEL: ERROR");
    }

    
    checkWaterLevelMillis = millis();
  }

  /* Activate LED Indicators */
  switch(FloatSwitch.SafetyLevel){
    case LEVEL_SAFE:
      digitalWrite(Indicators.Safe, HIGH);
      digitalWrite(Indicators.Warning, LOW);
      digitalWrite(Indicators.Danger, LOW);
      break;
    case LEVEL_WARNING:
      digitalWrite(Indicators.Safe, LOW);
      digitalWrite(Indicators.Warning, HIGH);
      digitalWrite(Indicators.Danger, LOW);
      break;
    case LEVEL_DANGER:
      digitalWrite(Indicators.Safe, LOW);
      digitalWrite(Indicators.Warning, LOW);
      digitalWrite(Indicators.Danger, HIGH);
      break;
    case LEVEL_ERROR:
      digitalWrite(Indicators.Safe, HIGH);
      digitalWrite(Indicators.Warning, HIGH);
      digitalWrite(Indicators.Danger, HIGH);
      break;
  }

  /* Check if safety level has changed */
  if(FloatSwitch.SafetyLevel != FloatSwitch.PreviousSafetyLevel){
    FloatSwitch.PreviousSafetyLevel = FloatSwitch.SafetyLevel;
    String msg = "Flood Monitoring Alert! \nFlood Level: " + String(FloatSwitch.SafetyLevel);
    //SendSMS(msg, adminContactNumber);
    SendSMS(msg, "+639159243835");
  }

  /* Send sensor readings to server every X time */
  if(millis() - datalogMillis > WEB_DATALOG_INTERVAL){
    if(Wifi.isConnected()){
      
      GPS.getSavedGPSParams(&savedGPSCoordinates);
      
      Serial.println("SENDING DATA TO WEB");
      String URL = "https://mactechph.com/iotfloodmonitoring/resources/data/sensorlog.php?u=" + String(Proximity.Value) + "&f=" + String(FloatSwitch.SafetyLevel) + "&lat=" + savedGPSCoordinates.lat + "&lng=" + savedGPSCoordinates.lng;
      String httpsResponse = HTTPClient.SendGetRequest(URL, false);
      byte delimiter[2];
      delimiter[0] = httpsResponse.indexOf('%');
      delimiter[1] = httpsResponse.indexOf('%', delimiter[0] + 1);

      adminContactNumber = "+" + httpsResponse.substring(delimiter[0] + 1, delimiter[1]);
 
      // Get admin contact number
      Serial.println("HTTPS RESPONSE: " + httpsResponse);
      Serial.println("FETCHED CONTACT NUMBER: " + adminContactNumber);
    }
    datalogMillis = millis();
  }

  /* Check for GPS Module Updates */
  bool isGPSActive = GPS.checkGPSUpdates(&freshGPSCoordinates);
  if(isGPSActive){
    // Save GPS Coordinates every X time
    if(millis() - gpsSavingMillis > EEPROM_SAVING_INTERVAL){
      if(freshGPSCoordinates.lat != savedGPSCoordinates.lat || freshGPSCoordinates.lng != savedGPSCoordinates.lng){
        // GPS Updated, Save to EEPROM 
        GPS.saveGPSParams(&freshGPSCoordinates);
        savedGPSCoordinates.lat = freshGPSCoordinates.lat;
        savedGPSCoordinates.lng = freshGPSCoordinates.lng;
        gpsSavingMillis = millis();
      }
    }
  }
  

}

void SendSMS(String message, String number){
  Serial.println("Sending SMS: " + message + " to " + number);               //Show this message on serial monitor
  sim800l.print("AT+CMGF=1\r");        
  delay(100);
  
  sim800l.print("AT+CMGS=\"" + String(number) + "\"\r");  //Your phone number don't forget to include your country code, example +212123456789"
  delay(500);
  
  sim800l.print(message);       //This is the text to send to the phone number, don't make it too long or you have to modify the SoftwareSerial buffer
  delay(500);
  
  sim800l.print((char)26);// (required according to the datasheet)
  delay(500);
 
  sim800l.println();
  Serial.println("Text Sent.");
  delay(500);
}
