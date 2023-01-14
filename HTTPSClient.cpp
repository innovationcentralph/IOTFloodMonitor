#include "HTTPSClient.h"

HTTPSClient::HTTPSClient(){

}

String HTTPSClient::SendGetRequest(String URL, bool secure){
  WiFiClientSecure *client = new WiFiClientSecure;
  if(client) {

    
    if(secure){
      
    }else{
      client -> setInsecure();
    }
    
    {
      // Add a scoping block for HTTPClient https to make sure it is destroyed before WiFiClientSecure *client is 
      HTTPClient https;
  
      Serial.print("[HTTPS] begin...\n");
      Serial.println("URL: " + URL);
      if (https.begin(*client, URL)) {  // HTTPS
        Serial.print("[HTTPS] GET...\n");
        // start connection and send HTTP header
        int httpCode = https.GET();
  
        // httpCode will be negative on error
        if (httpCode > 0) {
          // HTTP header has been send and Server response header has been handled
          Serial.printf("[HTTPS] GET... code: %d\n", httpCode);
  
          // file found at server
          if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
            String payload = https.getString();
            Serial.println(payload);
            return payload;
          }
        } else {
          
          Serial.printf("[HTTPS] GET... failed, error: %s\n", https.errorToString(httpCode).c_str());
          return "ERROR";
        }
  
        https.end();
      } else {
        Serial.printf("[HTTPS] Unable to connect\n");
        return "ERROR";
      }

      // End extra scoping block
    }
  
    delete client;
  } else {
    Serial.println("Unable to create client");
    return "ERROR";
  }
}
  
