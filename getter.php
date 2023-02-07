<?php
 include_once("DataProcessMySQL.php");
 $obj = new DataProcessMySQL();
$rows=$obj->readButtonData()->fetchAll();
$lastStatus=$rows[count($rows)-1]["button_status"];
print($lastStatus);
?>