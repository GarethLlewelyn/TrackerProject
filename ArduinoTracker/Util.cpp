#include "Util.h"
#include "utilities.h"
#include <esp_adc_cal.h> //Read battery library
#include <Arduino.h>



float Util::GetBattery(){

  esp_adc_cal_characteristics_t adc_chars;
  esp_adc_cal_characterize(ADC_UNIT_1, ADC_ATTEN_DB_11, ADC_WIDTH_BIT_12, 1100, &adc_chars);


  uint16_t battery_voltage = esp_adc_cal_raw_to_voltage(analogRead(BOARD_BAT_ADC_PIN), &adc_chars) * 2;
  float voltagePercentage = map(battery_voltage, 2700, 4100, 0, 100);
  return voltagePercentage;
}


void Util::RechargeState(){
  float Battery = GetBattery();
  while(Battery == -172.00){


    delay(1000);
    Serial.println("Recharge State");

  }
}


void Util::InitaliseSleep(int SleepTime){
  Serial.println("SLEEPY TIMKE");
  delay(200);
  esp_sleep_enable_timer_wakeup((SleepTime / 2) * 1000000ULL); // Sleeps for the selected ammount of time
  esp_light_sleep_start();
}





