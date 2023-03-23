<?php

$db = pg_connect("host=192.168.1.202 port=5432 dbname=mis_support user=postgres password=password") or die("Could not connect");
//$db = pg_connect("host=localhost port=5433 dbname=support_app user=postgres password=password") or die("Could not connect");
/*
  $sql_unit = pg_query($db, "select * from unit order by unit_name;") or die ("Could not match data because ".pg_last_error());
  $sql_support = pg_query($db, "select * from support;") or die ("Could not match data because ".pg_last_error());
  $sql_employee = pg_query($db, "select * from employee where approver=TRUE;") or die ("Could not match data because ".pg_last_error());  

*/
?>  
