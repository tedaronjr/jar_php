<?php
session_start();
set_time_limit(0);
?>

<!DOCTYPE html>
<html>
<head>
    <title>USER LOG IN</title>
<?php
include "scriptlink.php";

setlocale(LC_MONETARY,"en_PH.UTF-8"); 
?> 
<?php


 

if (isset($_POST['SUBMIT_USER'])) {

include "connect_mis_support.php";

$last_name = strtoupper($_POST['last_name']);
$employee_id = $_POST['employee_id'];


$sql_check = pg_query($db, "SELECT * FROM employee WHERE last_NAME='$last_name' AND id='$employee_id';") or die ("Could not match data because ".pg_last_error());
 
$num=pg_num_rows($sql_check);
$row = pg_fetch_assoc($sql_check);

 if ($num>0){

    $_SESSION['last_name'] = $last_name; 
    $_SESSION['employee_id'] = $employee_id;
    echo "<script>window.location.replace('feedback.php');</script>";
    //header('Location: feedback.php');


 }
 else {
   session_unset();
   session_destroy();
   echo "<script>alert('login failed!');</script>";   
 }

}
?> 

<script>
$(document).ready(function(){    
  $('#password').mask('000');
});

    function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>

</head>

<body>




<div class="container">
<BR>
  <h1 class="text-center"><span STYLE="color:blue;">M I S&nbsp;&nbsp; Ticket</span></h1><hr>
  <br>
   <h3 class="text-center">USER LOG IN</h3>
  <br>
 <form autocomplete="off" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <div class="row">
      <div class="col-3">
      </div>	
      <div class="col-3 text-center"><!--
&emsp;&emsp;&emsp;&emsp;&emsp;--><b>Username</b>
      </div> 
      <div class="col-3 text-center">
        <input style='text-transform: uppercase;' type="text" size=12 maxlength="20" class="form-control" placeholder="LAST NAME" id="user_name" name="last_name"  required  >
      </div> 
      <div class="col-3">
      </div>    
    </div> 

    <div class="row mt-2">
      <div class="col-3">
      </div>
      <div class="col-3 text-center">
	<!--&emsp;&emsp;&emsp;&emsp;&emsp;--><strong>Password</strong>
      </div>
      <div class="col-3">
        <input type="password" size=5 maxlength="20" onkeypress="return onlyNumberKey(event)" class="form-control" placeholder="EMPLOYEE ID" id="password" name="employee_id"  required  >
      </div> 
      <div class="col-3">
      </div>    
    </div>
<br>
    <div class="text-center">

            <button type="submit" class="btn btn-primary" id="SUBMIT" name="SUBMIT_USER">SUBMIT</button>
 
    </div> 







 </form>
 <BR><BR>
<?php
include "footer.php";
?>
 <!--
<BR><BR><BR><BR><BR><BR>
<hr>
<div class="text-center"><span style='font-weight:bold;font-size:12px;'>Created by: Ted Aron C. Cardona Jr. MIS Database Specialist&copy;&trade;</span></div>
-->
</body>
</html>
