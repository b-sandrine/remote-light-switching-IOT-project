<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
 include_once("DataProcessMySQL.php");
 $obj = new DataProcessMySQL();
 if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $obj->insertButtonData(
 $_REQUEST["buttonStatus"]
 );   
 }
?>