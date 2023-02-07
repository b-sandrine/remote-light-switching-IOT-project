#include <ESP8266WiFi.h>
const char* ssid = "RCA-WiFii";
const char* password = "@rca@2023";
const char* server = "iot.benax.rw";
const int serverPort = 80;
const int lightSensorPin = A0;
String lightStatus="ON";
String postPath = "/projects/4358b99ccf601a80b48163f403507691/LightSwitcher/backend.php";
String getPath = "/projects/4358b99ccf601a80b48163f403507691/LightSwitcher/getter.php";
String prevResponse = "ON";
String currentResponse = "";
const int relay = 4;
WiFiClient client;
void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  pinMode(lightSensorPin, INPUT);
  pinMode(relay, OUTPUT);
  digitalWrite(relay, LOW);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}
void loop() {
  int light=0;
  light = analogRead(lightSensorPin);
  if(isnan(light)){
    Serial.println("Failed to read from Light sensor.");
  }
  else{
    if (light > 500) {
      lightStatus="OFF";// If the light level is above the threshold
    } else {
      lightStatus="ON";
    }
  }
//   POST data to server
  if (client.connect(server, serverPort)) {
    String postData ="lightStatus="+lightStatus;
    upload(postData, "/projects/4358b99ccf601a80b48163f403507691/LightSwitcher/backend.php");
  } else {
    Serial.println("Could not connect to server");
  }
  // GET data from server
  if (client.connect(server, serverPort)) {
    client.print("GET " +(String)getPath + " HTTP/1.1\r\n");
    client.print("Host: " + String(server) + "\r\n");
    client.print("\r\n");
    String response = "";
    // wait for response
    while (client.connected()) {
      response += client.readStringUntil('\r');
    }
    if(response.indexOf("OFF") >= 0){
      currentResponse = "OFF";
     }
     else{
      currentResponse = "ON";
     }
     Serial.println(currentResponse);
     Serial.println(prevResponse);
     if(currentResponse != prevResponse){
        toogleLight(currentResponse);
      }
    prevResponse = currentResponse;
    currentResponse = "";
    response = "";
    client.stop();
  } else {
    Serial.println("Could not connect to server");
  }
  delay(500);
}
void upload(String data, const char* filepath){
       client.println("POST "+(String)filepath+" HTTP/1.1");
       client.println("Host: " + (String)server);
       client.println("User-Agent: ESP8266/1.0");
       client.println("Content-Type: application/x-www-form-urlencoded");
       client.println("Content-Length: " +(String)data.length());
       client.println();
       client.print(data);
       Serial.println("Uploading data...");
        parseResponse("Success");
  client.stop();
  }
void toogleLight(String action){
  Serial.println("action " + action);
  if(action == "ON"){
    digitalWrite(relay, LOW);
  }else{
    digitalWrite(relay,HIGH);
  }
}
void parseResponse(String success_msg){
 String datarx;
 while (client.connected()){
  String line = client.readStringUntil('\n');
    if (line == "\r") {
      break;
    }
 }
 while (client.available()){
  datarx += client.readStringUntil('\n');
 }
 if(datarx.indexOf(success_msg) >= 0){
  Serial.println("Uploaded.\n");
 }
 else{
  Serial.println("Failed.\n");
 }
 Serial.println("*******************\n");
 datarx = "";
}