#include "Ultrasonic.h"

Ultrasonic::Ultrasonic(byte trigger, byte echo) {
  triggerPin   = trigger;
  echoPin      = echo;
  
  pinMode(triggerPin, OUTPUT); 
  pinMode(echoPin, INPUT); 
}

int Ultrasonic::getProximity(){
  digitalWrite(triggerPin, LOW);
  delayMicroseconds(2);
  // Sets the trigPin on HIGH state for 10 micro seconds
  digitalWrite(triggerPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(triggerPin, LOW);
  
  // Reads the echoPin, returns the sound wave travel time in microseconds
  long duration = pulseIn(echoPin, HIGH);
  
  // Calculate the distance
  int distanceCm = duration * SOUND_VELOCITY/2;

  return distanceCm;
}
  
