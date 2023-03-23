<?php
session_start();
set_time_limit(0);
date_default_timezone_set('Asia/Manila');

include "connect_mis_support.php";

if (isset($_SESSION['last_name']) && isset($_SESSION['employee_id']) ) {
	
	


$sql_check = pg_query($db, "SELECT *,initcap(first_name) as firstname,initcap(last_name) as lastname FROM employee WHERE last_name='$_SESSION[last_name]' AND id='$_SESSION[employee_id]';") or die ("Could not match data because ".pg_last_error());
 
$num=pg_num_rows($sql_check);
$row = pg_fetch_assoc($sql_check);

 if ($num=0){


   session_unset();
   session_destroy();
   header('Location:user_login.php');  

 }
}
else
   header('Location:user_login.php');  
?>

 

