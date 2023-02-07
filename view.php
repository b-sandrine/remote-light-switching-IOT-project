<?php
 include_once("DataProcessMySQL.php");
 $obj = new DataProcessMySQL();
 print $obj->presentDataFromDB();
?>