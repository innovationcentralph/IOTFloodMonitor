// LED.h
#ifndef HTTPSCLIENT_h
#define HTTPSCLIENT_h

#include <WiFi.h>
#include <WiFiMulti.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Arduino.h>

class HTTPSClient{
  
  private:
    

  public:
    HTTPSClient();
    String SendGetRequest(String URL, bool secure);    // return int in cm units
    
};

#endif
