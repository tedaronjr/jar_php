<?php
    $num_pending=0;
    $num_wip=0;
    $num_revision=0;
    $num_done=0;

  $sql_mis = pg_query($db, "SELECT id, last_name, first_name, middle_name, approver, unit_id FROM public.employee where mis='T' and id=$_SESSION[employee_id];") or die ("Could not match data because ".pg_last_error()); 
  $num_mis=pg_num_rows($sql_mis);
  $row_mis = pg_fetch_assoc($sql_mis);

$mis=0;
if ($num_mis>0) {
  $mis=1;  
}

  $sql_approver = pg_query($db, "SELECT id, last_name, first_name, middle_name, approver, unit_id FROM public.employee where approver=TRUE and id=$_SESSION[employee_id] ;") or die ("Could not match data because ".pg_last_error()); 
  $num_approver=pg_num_rows($sql_approver);
  $row_approver = pg_fetch_assoc($sql_approver);

$approver=0;  
if ($num_approver>0) {
 $approver=1;  
}


  
if ( $approver == 1  && $mis==0 ) {
    $sql_pending = pg_query($db, "SELECT * FROM feedback WHERE (approver_id=$_SESSION[employee_id]) and status='PENDING'") or die ("Could not match data because ".pg_last_error()); 
    $num_pending=pg_num_rows($sql_pending); 

    $sql_done = pg_query($db, "SELECT * FROM feedback WHERE  (approver_id=$_SESSION[employee_id]) and status='DONE' ") or die ("Could not match data because ".pg_last_error());
    $num_done=pg_num_rows($sql_done);
 
    $sql_wip = pg_query($db, "SELECT * FROM feedback WHERE  (approver_id=$_SESSION[employee_id]) and status='IN PROGRESS' ") or die ("Could not match data because ".pg_last_error());
    $num_wip=pg_num_rows($sql_wip);
 
    $sql_revision = pg_query($db, "SELECT * FROM feedback WHERE  (approver_id=$_SESSION[employee_id]) and status='REVISION' ") or die ("Could not match data because ".pg_last_error());
    $num_revision=pg_num_rows($sql_revision); 

    $sql_approve = pg_query($db, "SELECT * FROM feedback WHERE  (approver_id=$_SESSION[employee_id] OR employee_id=$_SESSION[employee_id]) and status='FOR APPROVAL' ") or die ("Could not match data because ".pg_last_error());
    $num_approve=pg_num_rows($sql_approve);     
 }
 elseif ( $approver == 0 && $mis==1 ) {  
    $sql_pending = pg_query($db, "SELECT * FROM feedback WHERE status='PENDING' and approvedby='true';") or die ("Could not match data because ".pg_last_error()); 
    $num_pending=pg_num_rows($sql_pending);
   
    $sql_wip = pg_query($db, "SELECT * FROM feedback WHERE status='IN PROGRESS' and approvedby='true';") or die ("Could not match data because ".pg_last_error());
    $num_wip=pg_num_rows($sql_wip);
    
    $sql_revision = pg_query($db, "SELECT * FROM feedback WHERE status='REVISION' and approvedby='true';") or die ("Could not match data because ".pg_last_error());
    $num_revision=pg_num_rows($sql_revision); 
    
    $sql_done = pg_query($db, "SELECT * FROM feedback WHERE status='DONE' and approvedby='true';") or die ("Could not match data because ".pg_last_error());
    $num_done=pg_num_rows($sql_done);

    $sql_approve = pg_query($db, "SELECT * FROM feedback WHERE status='FOR APPROVAL';") or die ("Could not match data because ".pg_last_error());
    $num_approve=pg_num_rows($sql_approve);    
 }
 elseif ( $approver == 1 && $mis==1 ) {  
  $sql_pending = pg_query($db, "SELECT * FROM feedback WHERE status='PENDING' and approvedby='true';") or die ("Could not match data because ".pg_last_error()); 
  $num_pending=pg_num_rows($sql_pending);
 
  $sql_wip = pg_query($db, "SELECT * FROM feedback WHERE status='IN PROGRESS' and approvedby='true';") or die ("Could not match data because ".pg_last_error());
  $num_wip=pg_num_rows($sql_wip);
  
  $sql_revision = pg_query($db, "SELECT * FROM feedback WHERE status='REVISION' and approvedby='true';") or die ("Could not match data because ".pg_last_error());
  $num_revision=pg_num_rows($sql_revision); 
  
  $sql_done = pg_query($db, "SELECT * FROM feedback WHERE status='DONE' and approvedby='true';") or die ("Could not match data because ".pg_last_error());
  $num_done=pg_num_rows($sql_done);

  $sql_approve = pg_query($db, "SELECT * FROM feedback WHERE  (approver_id=$_SESSION[employee_id] OR employee_id=$_SESSION[employee_id]) and status='FOR APPROVAL' ;") or die ("Could not match data because ".pg_last_error());
  $num_approve=pg_num_rows($sql_approve);    
} 
 elseif ( $approver == 0 && $mis==0 ) {
    $sql_pending = pg_query($db, "SELECT * FROM feedback WHERE employee_id=$_SESSION[employee_id] AND status='PENDING' ;") or die ("Could not match data because ".pg_last_error()); 
    $num_pending=pg_num_rows($sql_pending); 

    $sql_done = pg_query($db, "SELECT * FROM feedback WHERE employee_id=$_SESSION[employee_id] AND status='DONE' ;") or die ("Could not match data because ".pg_last_error());
    $num_done=pg_num_rows($sql_done);
 
    $sql_wip = pg_query($db, "SELECT * FROM feedback WHERE  employee_id=$_SESSION[employee_id] AND status='IN PROGRESS' ;") or die ("Could not match data because ".pg_last_error());
    $num_wip=pg_num_rows($sql_wip);
 
    $sql_revision = pg_query($db, "SELECT * FROM feedback WHERE employee_id=$_SESSION[employee_id] AND  status='REVISION' ;") or die ("Could not match data because ".pg_last_error());
    $num_revision=pg_num_rows($sql_revision); 

    $sql_approve = pg_query($db, "SELECT * FROM feedback WHERE employee_id=$_SESSION[employee_id] AND  status='FOR APPROVAL' ;") or die ("Could not match data because ".pg_last_error());
    $num_approve=pg_num_rows($sql_approve);     
 } 
 
?>
<style>
   #exceptn:hover{
				   font-weight:bold;
                   color:blue;
                 }
				 
   #reminder:hover{
				   font-weight:bold;
                   color:blue;
                 }
				 
   #transdata:hover{
				   font-weight:bold;
                   color:blue;
                 }

   #covid:hover{
				   font-weight:bold;
                   color:blue;
                 }
   #prompt:hover{
				   font-weight:bold;
                   color:blue;
                 }
   #pending{
				   font-weight:bold;
				   
                   /*background-color: #fcba03;*/
				   
                 }					 
   #pending:hover{
				   font-weight:bold;
				   font-size:large;
                  /* color:red; */
                 }		
				 
   #mis{
	   font-size:large;
				   font-weight:bold;
                   color:blue;
         }	
   #wip{
	   
				   font-weight:bold;
                /*   background-color:blue;			   
				   color:white; */
         }
   #wip:hover{
	               font-size:large;	   
				   font-weight:bold;
				  /* background-color:blue; */
				   
                /*    color:yellow; */
         }	
		 
   #revision:hover {
	               font-size:large;
				   font-weight:bold;
                 
   }
   #revision {
	   
				   font-weight:bold;
                   background-color:red;
					color:white;
   }	
   
   #done:hover {
	               font-size:large;		   
				   font-weight:bold;
                 
   }   

   #done {
	   
				   font-weight:bold;
                   background-color: green ;
color:white;				   
   }	   

</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a id="mis" class="navbar-brand" href="feedback.php">MIS&nbsp;Ticket</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">


   <?php //if ( $num_pending > 0 ) { ?>	
      <li class="nav-item active">
        <a  class="nav-link bg-primary" href="pending.php"><SPAN STYLE="font-weight:bold;"></SPAN><b>Pending <?php echo $num_pending; ?></b></a>
      </li>
   <?php //} ?>	
   <?php //if ( $num_wip > 0 ) { ?>	   
      <li class="nav-item active">
        <a class="nav-link bg-warning" href="wip.php"><SPAN STYLE="font-weight:bold;"></SPAN><b>In Progress <?php echo $num_wip; ?></b></a>
      </li>	
   <?php //} ?>
   <?php //if ( $num_revision > 0 ) { ?>   
      <li class="nav-item active">
        <a class="nav-link bg-danger" href="revision.php"><SPAN STYLE="font-weight:bold;"></SPAN><b>Revision <?php echo $num_revision; ?></b></a>
      </li>	  
   <?php //} ?>	
   <?php //if ( $num_done > 0 ) { ?>     
      <li class="nav-item active">
        <a class="nav-link bg-success" href="done.php"><SPAN STYLE="font-weight:bold;"></SPAN><b>Done <?php echo $num_done; ?></b></a>
      </li>	
   <?php //} ?>	
   <?php //if ( $num_approve > 0 ) { ?>     
      <li class="nav-item active">
        <a  class="nav-link bg-info" href="approved.php"><SPAN STYLE="font-weight:bold;"></SPAN><b>For Approval <?php echo $num_approve; ?></b></a>
      </li>	
   <?php //} ?>	
   </ul>   	  
  </div>
  <b><?php echo $row['firstname'].' '.$row['lastname']; ?>&nbsp;<b>
<a  id="user_logout" href="user_logout.php">Log out</a>
</nav>