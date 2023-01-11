#include "Wifi.h"

byte reconnectCount = 0;
String _ssid = "";
String _password = "";

Wifi::Wifi(const char* ssid, const char* password){
  SSID      = ssid;
  PASSWORD  = password;

  _ssid = String(ssid);
  _password = String(password);
}


void WiFiStationConnected(WiFiEvent_t event, WiFiEventInfo_t info){
  Serial.println(F("Connected to AP successfully!"));
  reconnectCount = 0;
}

void WiFiGotIP(WiFiEvent_t event, WiFiEventInfo_t info){
  Serial.println(F("WiFi connected"));
  Serial.println(F("IP address: "));
  Serial.println(WiFi.localIP());
}

void WiFiStationDisconnected(WiFiEvent_t event, WiFiEventInfo_t info){
  Serial.println(F("Disconnected from WiFi access point"));
  Serial.print(F("WiFi lost connection. Reason: "));
  Serial.println(info.disconnected.reason);
  Serial.println(F("Trying to Connect to Wifi"));
  
  if(reconnectCount++ > 60){
    ESP.restart();
  }
  //Serial.println("WIFI RETRY: " + String(wifiRetry));
  WiFi.begin(_ssid.c_str(), _password.c_str());
}


void Wifi::connect(){
  
  WiFi.mode(WIFI_STA);
  WiFi.config(INADDR_NONE, INADDR_NONE, INADDR_NONE, INADDR_NONE);
  //WiFi.setHostname("SAMPLE_HOSTNAME");

  WiFi.onEvent(WiFiStationConnected, SYSTEM_EVENT_STA_CONNECTED);
  WiFi.onEvent(WiFiGotIP, SYSTEM_EVENT_STA_GOT_IP);
  WiFi.onEvent(WiFiStationDisconnected, SYSTEM_EVENT_STA_DISCONNECTED);
  
  WiFi.begin(SSID, PASSWORD);

  reconnectCount = 0;
  while (WiFi.status() != WL_CONNECTED) {
      Serial.print(F("."));
      reconnectCount++;
      if(reconnectCount > 25){
        break;
      }
      delay(100);
  }
  WiFi.setAutoReconnect(true);
  WiFi.setSleep(false);
}

bool Wifi::isConnected(){
  if(WiFi.status() == WL_CONNECTED){
    return true;
  }else{
    return false;
  }
}



  
