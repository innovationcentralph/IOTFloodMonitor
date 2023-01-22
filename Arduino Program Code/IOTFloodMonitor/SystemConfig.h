//Pin definitions
#define FLOAT_CRITICAL 26
#define FLOAT_SAFE     27

// Macro definitions
#define MAX_PROXIMITY 100
#define MIN_PROXIMITY 10

// Float switch States
#define NOT_TRIGGERED      HIGH
#define TRIGGERED  LOW

// Danger Levels
#define LEVEL_SAFE     1
#define LEVEL_WARNING  2
#define LEVEL_DANGER   3
#define LEVEL_ERROR    99

// LED Indicators
#define LED_SAFE      32
#define LED_WARNING   33
#define LED_DANGER    25



  
// Pin Config
struct FloatSwitch{
  
  struct Pins{
    const byte Critical = FLOAT_CRITICAL;
    const byte Safe     = FLOAT_SAFE;
  }Pins;

  // Danger Level
  byte SafetyLevel = 0;
  byte PreviousSafetyLevel = 0;

  // Float switch states
  bool criticalLevel = 0;
  bool safeLevel     = 0; 
  
}FloatSwitch;

struct Proximity{

  struct Pins{
    const byte Trigger = 12;
    const byte Echo    = 14;
  }Pins;
  
  struct Threshold{
    byte Min = MIN_PROXIMITY;
    byte Max = MAX_PROXIMITY;
  }Threshold;

  //Percentage Reading
  int Value;

}Proximity;

struct Indicators{
  const byte Safe    = LED_SAFE;
  const byte Warning = LED_WARNING;
  const byte Danger  = LED_DANGER;
}Indicators;
  
