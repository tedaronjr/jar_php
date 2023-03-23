
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

   function RemoveSpecialChar($str) {
 
    // Using str_replace() function
    // to replace the word
    $res = str_replace( array( '\'', '"' ), ' ', $str);
  
    // Returning the result
    return $res;
    }

   if (isset($_POST['SUBMIT_REVISION'])) {
      $id=$_POST['id'];
      $status='PENDING';
      $remarks= RemoveSpecialChar($_POST['remarks']);
      $update_sql = pg_query($db, "
  update feedback set date_create=current_timestamp,status='$status',remarks='$remarks',tag_revision='T' where id=$id;
    ") or die ("Could not match data because ".pg_last_error());     
    }
    elseif (isset($_POST['SUBMIT_REMOVED'])) {
      $id=$_POST['id'];
      $status='REMOVED';
      $update_sql = pg_query($db, "
  update feedback set date_create=current_timestamp,status='$status' where id=$id;
    ") or die ("Could not match data because ".pg_last_error());         
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

        function editfeedback(param) {
                    
                    $.ajax({
                        url : "edit_feedback.php",
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
  <div class="bg-danger text-center text-white"><span  style='font-weight:bold;font-size:18px;'>MIS Revision Ticket</span></div>
  <br>  
  <form onsubmit="return confirm('Are you sure you want to pending?');" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post" >
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
  while($row_revision = pg_fetch_assoc($sql_revision)) 
  {
    $sql_loc = pg_query($db, "select * from feedback_location where id=$row_revision[loc_id];") or die ("Could not match data because ".pg_last_error());
    $row_loc = pg_fetch_assoc($sql_loc);
    $sql_fbtype = pg_query($db, "select * from feedback_type where id=$row_revision[fb_id];") or die ("Could not match data because ".pg_last_error());
    $row_fbtype = pg_fetch_assoc($sql_fbtype);    
    $sql_support = pg_query($db, "select * from support where id=$row_revision[support];") or die ("Could not match data because ".pg_last_error());	
    $row_support = pg_fetch_assoc($sql_support);    

    $sql_unit = pg_query($db, "select * from unit order by unit_name;") or die ("Could not match data because ".pg_last_error());
    $sql_user = pg_query($db, "select * from employee where id=$row_revision[employee_id];") or die ("Could not match data because ".pg_last_error());	 
    $sql_employee = pg_query($db, "select * from employee where approver=TRUE;") or die ("Could not match data because ".pg_last_error());	 
    $sql_approver = pg_query($db, "select * from employee where approver=TRUE and id=$row_revision[approver_id];") or die ("Could not match data because ".pg_last_error());
    $row_approver = pg_fetch_assoc($sql_approver);
    $row_user = pg_fetch_assoc($sql_user);	 
    $n=$x + 1;
    //echo "<form autocomplete=\"off\" role=\"form\" id=\"form1\"  method=\"POST\" target=\"_self\" >";
	  echo '<tr>';
      $date_time=date_create("$row_revision[date_create]");
      $date_time=date_format($date_time,"m-d-Y h:i:s A");
      $effectivity=date_create("$row_revision[required_date]");
      $effectivity=date_format($effectivity,"m-d-Y");
      echo "<td>$row_revision[id]</td>";//ticket no

      echo "<td>$date_time</td>";//date time
      echo "<td>$row_user[first_name] $row_user[last_name]</td>";//requester
      echo "<td>$row_loc[location_name]</td>";//location
      echo "<td>$row_fbtype[feedback_name]</td>";//type of request
      echo "<td>$row_support[support_name]</td>";//program
      if ($row_revision['support']==1) {
       echo "<td>$row_revision[lrcs_acctno]<br>$row_revision[lrcs_loan] $row_revision[lrcs_ser]</td>";//account no 
       echo "<td>$row_revision[lrcs_err]</td>";//error message
       echo "<td>N/A</td>";//specific concern 
       echo "<td>$row_revision[lrcs_des]</td>";//description
      } 
      elseif ($row_revision['support']==2) {
       echo "<td>$row_revision[portal_acctno]</td>";//account no
       echo "<td>N/A</td>";//error message
       echo "<td>$row_revision[portal_opt]</td>";//specific concern
       echo "<td>$row_revision[portal_des]</td>";//description
      } 
      elseif ($row_revision['support']==3) {            
       echo "<td>N/A</td>";//account no
       echo "<td>$row_revision[ict_err]</td>";//error message
       echo "<td>$row_revision[ict_opt]</td>";//specific concern
       echo "<td>$row_revision[ict_des]</td>";//description
      }
      elseif ($row_revision['support']==4) {            
        echo "<td>N/A</td>";//account no
        echo "<td>N/A</td>";//error message
        echo "<td>N/A</td>";//specific concern
        echo "<td>$row_revision[other_des]</td>";//description
       }       
      echo "<td>$effectivity</td>";//EFFECTIVITY
      echo "<td>$row_approver[first_name] $row_approver[last_name]</td>";//Approver
      echo "<td><a href='uploads/$row_revision[attach_]' target=_blank>$row_revision[attach_]</a> <input type=\"hidden\"   name=\"id\"  value=\"$row_revision[id]\"   /></td>";//Attachment
      if ( $approver == 0 && $mis==1 ) {	  
        echo "<td class='text-center'>
        <a    href=\"#\" id=\"name\" value=$row_revision[id] onclick=\"TestFunction('$row_revision[id]')\" >History</a>
        </td>";	  
      }
      elseif ( $approver == 0 && $mis==0) {	  
        echo "<td class='text-center'> <a class='btn bg-primary'    href=\"#\" id=\"name\" value=$row_revision[id] onclick=\"editfeedback('$row_revision[id]')\" >UPDATE</a>
        <br><a    href=\"#\" id=\"name\" value=$row_revision[id] onclick=\"TestFunction('$row_revision[id]')\" >History</a>
        </td>";	  
      }
      elseif ( $approver == 1 && $mis==0 ) {	  
        echo "<td class='text-center'>
        <a    href=\"#\" id=\"name\" value=$row_revision[id] onclick=\"TestFunction('$row_revision[id]')\" >History</a>
        </td>";	 
      } 
      elseif ( $approver == 1 && $mis==1 ) {	  
        echo "<td class='text-center'>
        <a    href=\"#\" id=\"name\" value=$row_revision[id] onclick=\"TestFunction('$row_revision[id]')\" >History</a>
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
 




