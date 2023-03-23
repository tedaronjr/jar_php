<?php
/* I used PHP becase i need more functions, this only a example how
 * to creat and call a modal after click on a link.
*/
include "connect_mis_support.php";
$sql_history = pg_query($db, "SELECT * FROM feedback_history WHERE feedback_id =$_POST[id];") or die ("Could not match data because ".pg_last_error()); 
$num_history=pg_num_rows($sql_history);
 
//function create_modal($modal_ID = 'myModal')
//{
//    return "<div class='modal fade' id='$modal_ID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
/*  echo  "<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>  
				<div class='modal-dialog'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>                      
                    </div>
                    <div class='modal-body'>";
*/
?> 
<style>
  .modal-body{
    height: 400px;
    overflow-y: auto;
}
</style>  
 <div class="modal" id="myModal" >
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
     <div class="modal-content">
      
        
        <div class="modal-header">
         <h4 class="modal-title">Ticket History</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
 <div class="table-responsive">   
        
    <table class="table table-bordered table-hover w-auto small"  >
    <thead>        
    <tr> 
      <td ><b>Date | Time</b></td>	
      <td ><b>Status</b></td>
      <td><b>Remarks</b></td>
      <td ><b>Updated by</b></td>		
    </tr>
    </thead>
    <tbody >            
    

<?php

 while($row_history = pg_fetch_assoc($sql_history)) 
	{
    echo '<tr>';

    if ($row_history['status']=="IN PROGRESS" || $row_history['status']=="DONE" || $row_history['status']=="REVISION" ) {    
    $sql_mis = pg_query($db, "SELECT * FROM employee WHERE id =$row_history[mis_id];") or die ("Could not match data because ".pg_last_error()); 
    $num_mis=pg_num_rows($sql_mis);
    $row_mis=pg_fetch_assoc($sql_mis);
    $misname=$row_mis['first_name'].' '.$row_mis['last_name'];
    }
    elseif ($row_history['status']=="FOR APPROVAL") {    
      $sql_mis = pg_query($db, "SELECT * FROM employee WHERE id =$row_history[employee_id];") or die ("Could not match data because ".pg_last_error()); 
      $num_mis=pg_num_rows($sql_mis);
      $row_mis=pg_fetch_assoc($sql_mis);
      $misname=$row_mis['first_name'].' '.$row_mis['last_name'];
    } 
    elseif ($row_history['status']=="PENDING" && $row_history['tag_revision']=="T") {    
      $sql_mis = pg_query($db, "SELECT * FROM employee WHERE id =$row_history[employee_id];") or die ("Could not match data because ".pg_last_error()); 
      $num_mis=pg_num_rows($sql_mis);
      $row_mis=pg_fetch_assoc($sql_mis);
      $misname=$row_mis['first_name'].' '.$row_mis['last_name'];
    }    
    elseif ($row_history['status']=="PENDING" && $row_history['tag_revision']<>"T") {    
      $sql_mis = pg_query($db, "SELECT * FROM employee WHERE id =$row_history[approver_id];") or die ("Could not match data because ".pg_last_error()); 
      $num_mis=pg_num_rows($sql_mis);
      $row_mis=pg_fetch_assoc($sql_mis);
      $misname=$row_mis['first_name'].' '.$row_mis['last_name'];
    }         
    $d=date_create("$row_history[date_create]");
    $d=date_format($d,"m-d-Y | h:i:s A");    
    echo "<td>$d</td>";//date time
    if ($row_history['status']=="FOR APPROVAL")
    echo "<td class='bg-info'>$row_history[status]</td>";//status    
    elseif ($row_history['status']=="PENDING")
    echo "<td class='bg-primary'>$row_history[status]</td>";//status
    elseif ($row_history['status']=="IN PROGRESS")
    echo "<td class='bg-warning'>$row_history[status]</td>";//status
    elseif ($row_history['status']=="DONE")
    echo "<td class='bg-success'>$row_history[status]</td>";//status
    elseif ($row_history['status']=="REVISION")
    echo "<td class='bg-danger'>$row_history[status]</td>";//status        
    
    if ($row_history['status']=="PENDING" && $row_history['tag_revision']=="T")
    echo "<td>$row_history[remarks]</td>";//remarks    
    elseif ($row_history['status']=="FOR APPROVAL" || $row_history['status']=="PENDING" || $row_history['status']=="IN PROGRESS" || $row_history['status']=="DONE")
    echo "<td></td>";//remarks    
    else    
    echo "<td>$row_history[remarks]</td>";//remarks

      echo "<td>$misname</td>";//Updated by

     echo '</tr>';

	 }
?> 
    </tbody>            
</table> 
 </div> 
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
 </div>
 <?php 
//}
//echo create_modal();

?>