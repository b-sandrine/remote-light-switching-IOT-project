<?php
 class DatabaseManager{
 private $host="localhost";
 private $user="benax_iot_root";
 private $db="benax_iot";
 private $pass="Td(FAdeZ9xp3";
 private $con;
 public function __construct(){
 try{
 #connecting the database
 $this->con = new PDO("mysql:host=".$this->host."; dbname=".$this->db, $this->user, $this->pass);
 #Setting connection attributes
 $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 return $this->con;
 }
 catch(PDOException $error){
 echo $error->getMessage();
 }
 }
 }
 ?>