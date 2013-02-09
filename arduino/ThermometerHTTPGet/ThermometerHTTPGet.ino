#include <SPI.h>
#include <WiFly.h>

#include "Credentials.h"
//int dispPin; 
/*
* setup() – this function runs once when you turn your Arduino on
* We the three control pins to outputs
*/
int temperaturePin = 0;

void setup()
{

  Serial.begin(9600);                             
  WiFly.begin();
  
  
  
  if (!WiFly.join(ssid, passphrase)) {
    Serial.println("Association failed.");
    while (1) {
      // Hang on failure.
    }
  }
  
}
 
 
/*
* loop() - this function will start after setup finishes and then repeat
*/
void loop()                     // run over and over again
{
 float temperature = getVoltage(temperaturePin);  //getting the voltage reading from the temperature sensor
 temperature = (temperature - .5) * 100;          //converting from 10 mv per degree wit 500 mV offset
                                                  //to degrees ((volatge - 500mV) times 100)


  //Serial.println("connecting...");
  WiFlyClient client("www.tonyhaddon.com", 80);
  if (client.connect()) {
    Serial.println("connected");
    client.print("GET http://www.tonyhaddon.com/thermog-tweet/twitteroauth-master/tweet.php?tmp=");
    client.print(temperature);
    client.print("&scrt=tellno1 HTTP/1.0");
    client.print("\r\n");
    client.println("From: tony@tonyhaddon.com");
    client.println("User-Agent: ThermoG/0.1");
    client.println();
    
    Serial.println("Request sent.");
    Serial.println(temperature);
  } else {
    Serial.println("connection failed");
  }
  
  while(client.available()) {
    char c = client.read();
    Serial.print(c);
  }
                                             
 
 delay(600000); // Ten Minutes
//delay(60000); // One Minutes
 //delay(10000); // Ten Seconds
}


/*
 * getVoltage() - returns the voltage on the analog input defined by
 * pin
 */
float getVoltage(int pin){
 return (analogRead(pin) * .004882814); //converting from a 0 to 1024 digital range
                                        // to 0 to 5 volts (each 1 reading equals ~ 5 millivolts
}
