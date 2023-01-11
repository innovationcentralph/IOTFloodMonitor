// LED.h
#ifndef ULTRASONIC_h
#define ULTRASONIC_h

#include <Arduino.h>

#define SOUND_VELOCITY 0.034
#define CM_TO_INCH 0.393701

class Ultrasonic{
  
  private:
    byte triggerPin;
    byte echoPin;

  public:
    Ultrasonic(byte trigger, byte echo);
   
    int getProximity();    // return int in cm units
    
};

#endif
