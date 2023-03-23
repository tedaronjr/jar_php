<?php
include "connect_mis_support.php";

?>
<!DOCTYPE html>
<html>
<head>
<?php  

if (isset($_POST['submit'])) {

$feedback=$_POST['feedback_opt'];   
$program_=$_POST['program_opt'];
$department=$_POST['dept_opt'];
$requester_id=$_POST['req_id'];
$status="PENDING";
$req_location=$_POST['loc_opt'];
$approver_id=$_POST['approver_id'];
$attach_file=$_POST['attach'];
$feedback_other=$_POST['other_feedback_text'];  
$required_date=$_POST['required_date']; 

 
if ($program_=="LRCS") {

  $acctno =$_POST['lrcs_acctno'];
  $loan =$_POST['lrcs_loan'];
  $loanser =$_POST['lrcs_ser'];
  $error_msg =$_POST['lrcs_err'];
  $description=$_POST['lrcs_des'];

$sql_insert = pg_query($db, "INSERT INTO public.lrcs_support(
  feedback, department, requester_id, status, req_location, 
  date_create, approver_id, attach_file, acctno, loan, loanser, 
  error_msg, description,feedback_other,required_date )
VALUES ('$feedback',  '$department', $requester_id, '$status', '$req_location', 
  current_timestamp, $approver_id, '$attach_file', '$acctno', '$loan', '$loanser', 
  '$error_msg', '$description','$feedback_other','$required_date');") or die ("Could not match data because ".pg_last_error());

}
elseif  ($program_=="PORTAL") {

  $acctno =$_POST['portal_acctno'];
  $concern =$_POST['portal_opt'];
  $description=$_POST['portal_des'];

  $sql_insert = pg_query($db, "INSERT INTO public.portal_support(
    feedback,  department, requester_id, status, req_location, 
    date_create, approver_id, attach_file, acctno, concern,description,feedback_other,required_date 
    )
  VALUES ('$feedback',  '$department', $requester_id, '$status', '$req_location', 
    current_timestamp, $approver_id, '$attach_file', '$acctno', 
    '$concern', '$description','$feedback_other','$required_date');") or die ("Could not match data because ".pg_last_error());
  
}

elseif  ($program_=="ICT") {

  $concern =$_POST['ict_opt'];
  $error_msg =$_POST['ict_err'];
  $description=$_POST['ict_des'];

  $sql_insert = pg_query($db, "INSERT INTO public.ict_support(
    feedback,  department, requester_id, status, req_location, 
    date_create, approver_id, attach_file, error_msg, concern,description,feedback_other,required_date 
    )
  VALUES ('$feedback',  '$department', $requester_id, '$status', '$req_location', 
    current_timestamp, $approver_id, '$attach_file', '$error_msg', 
    '$concern', '$description','$feedback_other','$required_date');") or die ("Could not match data because ".pg_last_error());
  
} 
echo "<script>window.location.replace(\"feedback_form.php\")</script>";

}



?>  

<?php
  $sql_lrcs = pg_query($db, "select * from lrcs_support where status='PENDING' order by date_create ;") or die ("Could not match data because ".pg_last_error());    
?>
<?php
include "scriptlink.php";
?>
 <script>  
 $(document).ready(function(){  
	$('#view_data').DataTable( {"bLengthChange": false,"searching": false,
	paging: false,"lengthMenu": [["All"], [5, 25, 50, "All"]]
	}
	);  
 });  
 </script>
</head>
<body>


<div class="container-fluid">

 <br>
 <h2 class="text-center">VIEW M.I.S FEEDBACK </h2><hr>
 <br>
 <div class="table-responsive">  

 <table id="view_data" class="table table-striped table-bordered">

                          <thead>  
                               <tr>      
                                    <td>LOCATION</td>  
                                    <td>REQUESTER</td> 
                                    <td>DEPARTMENT</td> 
                                    <td>ATTACH FILE</td>
                               </tr>  
                          </thead> 
<?php                          
 while($row_lrcs = pg_fetch_assoc($sql_lrcs)) {
    $sql_requester = pg_query($db, "select * from employee where id=$row_lrcs[requester_id];") or die ("Could not match data because ".pg_last_error());	 
    $row_requester = pg_fetch_assoc($sql_requester);

    echo '<tr>';
    echo "<td>$row_lrcs[req_location]</td>";
    echo "<td>$row_requester[last_name],$row_requester[first_name]</td>";
    echo "<td>$row_lrcs[department]</td>";
    echo "<td><a href=\"uploads/$row_lrcs[attach_file]\">$row_lrcs[attach_file]</a></td>";
    echo '</tr>';


 }                
?>                          
</table>
</div>

</div>
</body>
</html> 
 




