
<?php
include "user_session.php";
?>
<!DOCTYPE html>
<html>
<head>
<style>  
@media (max-width: 400px) {  
    .table-responsive {
        font-size:10px !important;
    }
}
</style>  
<?php
   include "scriptlink.php";
   setlocale(LC_MONETARY,"en_PH.UTF-8"); 

   if (isset($_POST['SUBMIT_APPROVED'])) {
      $id=$_POST['id'];
      $status='PENDING';
      $update_sql = pg_query($db, "
  update feedback set date_create=current_timestamp,status='$status',approvedby=true where id=$id;
    ") or die ("Could not match data because ".pg_last_error());   
    echo "<script>window.location.replace('approved.php');</script>";
  
    }
        
?>   
<script>
$(document).ready(function(){  
	$('#fb_tbl').DataTable( {"bLengthChange": false,"searching": true,
	paging: true,"lengthMenu": [[12, 25, 50, -1], [12, 25, 50, "All"]]
	}
	);  
 }); 
 
 function TestFunction(param) {
                    
                    $.ajax({
                        url : "modalresult.php",
                        type : "post",
                        dataType:"text",
                        data : {
                             id: param
                        },
                        success : function (a){
                            $('#result').html(a);
							$('#myModal').modal('show');
                        }
                    });
        }	
 </script>
 
</head>

<body>

<?php
include "navlink.php";
?>

<div class="container-fluid">
  <br>
  <div class="bg-info text-center"><span  style='font-weight:bold;font-size:18px;'>MIS For Approval Ticket</span></div>
  <br>  
  <form onsubmit="return confirm('Are you sure?');" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post" >
  <div class="table-responsive">  
    <table id="fb_tbl" class="table table-bordered table-hover w-auto small" >
    <thead>        
    <tr> 
    <td><b>Ticket No.</b></td>	

      <td ><b>Date | Time</b></td>	
      <td ><b>Requester</b></td>
      <td><b>Location</b></td>
      <td ><b>Type of Request</b></td>	
      <td><b>Program</b></td>	
      <td ><b>Account No.</b></td>	
      <td ><b>Error Message</b></td>	
      <td ><b>Specific Concern</b></td>	
      <td><b>Description</b></td>	
      <td ><b>Effectivity Date</b></td>	
      <td ><b>Approver</b></td>	
      <td><b>Attachment</b></td>
      <td><b>Action</b></td>	

    </tr>
    </thead>
    <tbody>            
<?php 	
  $x=0;
  while($row_approve = pg_fetch_assoc($sql_approve)) 
  {
    $sql_loc = pg_query($db, "select * from feedback_location where id=$row_approve[loc_id];") or die ("Could not match data because ".pg_last_error());
    $row_loc = pg_fetch_assoc($sql_loc);
    $sql_fbtype = pg_query($db, "select * from feedback_type where id=$row_approve[fb_id];") or die ("Could not match data because ".pg_last_error());
    $row_fbtype = pg_fetch_assoc($sql_fbtype);    
    $sql_support = pg_query($db, "select * from support where id=$row_approve[support];") or die ("Could not match data because ".pg_last_error());	
    $row_support = pg_fetch_assoc($sql_support);    

    $sql_unit = pg_query($db, "select * from unit order by unit_name;") or die ("Could not match data because ".pg_last_error());
    $sql_user = pg_query($db, "select * from employee where id=$row_approve[employee_id];") or die ("Could not match data because ".pg_last_error());	 
    $sql_employee = pg_query($db, "select * from employee where approver=TRUE;") or die ("Could not match data because ".pg_last_error());	 
    $sql_approver = pg_query($db, "select * from employee where approver=TRUE and id=$row_approve[approver_id];") or die ("Could not match data because ".pg_last_error());
    $row_approver = pg_fetch_assoc($sql_approver);
    $row_user = pg_fetch_assoc($sql_user);	 
    $n=$x + 1;
    //echo "<form autocomplete=\"off\" role=\"form\" id=\"form1\"  method=\"POST\" target=\"_self\" >";
	  echo '<tr>';
      $date_time=date_create("$row_approve[date_create]");
      $date_time=date_format($date_time,"m-d-Y h:i:s A");
      $effectivity=date_create("$row_approve[required_date]");
      $effectivity=date_format($effectivity,"m-d-Y");
      echo "<td>$row_approve[id]</td>";//ticket no
      
      echo "<td>$date_time</td>";//date time
      echo "<td>$row_user[first_name] $row_user[last_name]</td>";//requester
      echo "<td>$row_loc[location_name]</td>";//location
      echo "<td>$row_fbtype[feedback_name]</td>";//type of request
      echo "<td>$row_support[support_name]</td>";//program
      if ($row_approve['support']==1) {
       echo "<td>$row_approve[lrcs_acctno]<br>$row_approve[lrcs_loan] $row_approve[lrcs_ser]</td>";//account no 
       echo "<td>$row_approve[lrcs_err]</td>";//error message
       echo "<td>N/A</td>";//specific concern 
       echo "<td>$row_approve[lrcs_des]</td>";//description
      } 
      elseif ($row_approve['support']==2) {
       echo "<td>$row_approve[portal_acctno]</td>";//account no
       echo "<td>N/A</td>";//error message
       echo "<td>$row_approve[portal_opt]</td>";//specific concern
       echo "<td>$row_approve[portal_des]</td>";//description
      } 
      elseif ($row_approve['support']==3) {            
       echo "<td>N/A</td>";//account no
       echo "<td>$row_approve[ict_err]</td>";//error message
       echo "<td>$row_approve[ict_opt]</td>";//specific concern
       echo "<td>$row_approve[ict_des]</td>";//description
      }
      elseif ($row_approve['support']==4) {            
        echo "<td>N/A</td>";//account no
        echo "<td>N/A</td>";//error message
        echo "<td>N/A</td>";//specific concern
        echo "<td>$row_approve[other_des]</td>";//description
       }       
      echo "<td>$effectivity</td>";//EFFECTIVITY
      echo "<td>$row_approver[first_name] $row_approver[last_name]</td>";//Approver
      echo "<td><a href='uploads/$row_approve[attach_]' target=_blank>$row_approve[attach_]</a> <input type=\"hidden\"   name=\"id\"  value=\"$row_approve[id]\"   /></td>";//Attachment
      if ( $approver == 0 && $mis==1 ) {	  
        echo "<td class='text-center'>
        <a    href=\"#\" id=\"name\" value=$row_approve[id] onclick=\"TestFunction('$row_approve[id]')\" >History</a>
        </td>";	  
      }
      elseif ( $approver == 0 && $mis==0) {	  
        echo "<td class='text-center'>
        <a    href=\"#\" id=\"name\" value=$row_approve[id] onclick=\"TestFunction('$row_approve[id]')\" >History</a>
        </td>";	  
      }
      elseif ( $approver == 1 && $mis==0 && $row_approve['approvedby']<>'true' ) {	  
        echo "<td class='text-center'><input type=\"submit\" class=\"btn btn-primary\"  name=\"SUBMIT_APPROVED\"  value=\"APPROVE\"   />
        <br><a    href=\"#\" id=\"name\" value=$row_approve[id] onclick=\"TestFunction('$row_approve[id]')\" >History</a>
        </td>";	 
      } 
      elseif ( $approver == 1 && $mis==1 && $row_approve['approvedby']<>'true' ) {	  
        echo "<td class='text-center'><input type=\"submit\" class=\"btn btn-primary\"  name=\"SUBMIT_APPROVED\"  value=\"APPROVE\"   />
        <br><a    href=\"#\" id=\"name\" value=$row_approve[id] onclick=\"TestFunction('$row_approve[id]')\" >History</a>
        </td>";	 
      }                         

    echo '</tr>'; 	  
	  echo "</form>";
    ++$x;  
  } 
?>  
    </tbody>
   </table>  
 </div> <!-- end table responsive -->
 </form>
 <div id="result"></div>

 <BR><BR>
<?php
include "footer.php";
?> 
</div>
</body>
</html> 
 




