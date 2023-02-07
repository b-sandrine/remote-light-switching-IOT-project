<?php
include_once "DatabaseManager.php";
class DataProcessMySQL extends DatabaseManager{
 private $LIGHTER_TBL = "";
 private $BUTTON_TBL="";
 public function __construct($tbl="g1_lighter",$btn="g1_button"){
 $this->LIGHTER_TBL = $tbl;
 $this->BUTTON_TBL=$btn;
 #Start by creating a table in the database if it doesn't exist yet.
 $sql = "CREATE TABLE IF NOT EXISTS ".$this->LIGHTER_TBL." (
 id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
 light_status VARCHAR(10) NOT NULL,
 timestamp varchar(50) NOT NULL)
 ENGINE=InnoDB DEFAULT CHARSET=latin1";
 $q = parent::__construct()->prepare($sql);
 $q->execute();
 
 $btnSql="CREATE TABLE IF NOT EXISTS ".$this->BUTTON_TBL." (
 id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
 button_status VARCHAR(10) NOT NULL,
 timestamp varchar(50) NOT NULL)
 ENGINE=InnoDB DEFAULT CHARSET=latin1";
 $btnQ=parent::__construct()->prepare($btnSql);
 $btnQ->execute();
 }
 public function getTimestamp($offsetParam){
 #Offset is the number of hours counted from GMT.
 $offsetToSeconds=$offsetParam*60*60; #converting offset to seconds
 $dateFormat="Y-m-d H:i:s";
 return gmdate($dateFormat, time()+$offsetToSeconds);
 }
 public function insertData($lightStatus){
print($lightStatus);
 $sql ="INSERT INTO ".$this->LIGHTER_TBL."(
 light_status,
 timestamp
 ) VALUES (
 :light_status,
 :timestamp
 )";
 $q = parent::__construct()->prepare($sql);
 $q->bindValue(":light_status", $lightStatus);
 $q->bindValue(":timestamp", $this->getTimestamp(2));
 if($q->execute()){
 print "Success";
 }
 else{
 print "Failure";
 }
 }
 public function insertButtonData($buttonStatus){
 $sql ="INSERT INTO ".$this->BUTTON_TBL."(
 button_status,
 timestamp
 ) VALUES (
 :button_status,
 :timestamp
 )";
 $q = parent::__construct()->prepare($sql);
 $q->bindValue(":button_status", $buttonStatus);
 $q->bindValue(":timestamp", $this->getTimestamp(2));
 if($q->execute()){
 print "Success";
 }
 else{
 print "Failure";
 }
 }
public function readData(){
 $sql ="SELECT * FROM ".$this->LIGHTER_TBL;
 $q = parent::__construct()->prepare($sql);
 $q->execute();
 return $q;
 }
 public function readButtonData(){
 $sql ="SELECT * FROM ".$this->BUTTON_TBL;
 $q = parent::__construct()->prepare($sql);
 $q->execute();
 return $q;
 }
 public function presentDataFromDB(){
 print "<style>";
 print "
 table {
 border-spacing: 10px;
 border-collapse: collapse;
 }
 td,th{
 border: 1px solid #AAA;
 padding: 5px;
 text-align: left;
 }
 ";
 print "</style>";
 print "<table>";
 print "<tbody>";
 print "<tr>";
 print "<th>Timestamp</th>";
 print "<th>Light status</th>";
 print "</tr>";
 #Parse the content to get line after line
 foreach($this->readData()->fetchAll() as $row):
 if(!empty($row)){
 $timestamp=$row["timestamp"];
 $lightStatus=$row["light_status"];
 print "<tr>";
 print "<td>".$timestamp."</td>";
 print "<td>".$lightStatus."</td>";
 print "</tr>";
 }
 endforeach;
 print "</tbody>";
 print "</table>";
 }
 public function createAPI(){
 $response = array();
 $sql = "
 SELECT
 light_status,
 timestamp
 FROM ".$this->LIGHTER_TBL;
 $q = parent::__construct()->prepare($sql);
 $q->execute();
 if($q->rowCount()>0){
 while($row=$q->fetch(PDO::FETCH_ASSOC)){
 extract($row);
 $response[] = $row;
 }
 print "{\"data\":".json_encode($response)."}";
 }
 else{
 echo "{\"data\":[
 {\"lightStatus\":\"null\",
}]}";
 }
 } #End Create API
}