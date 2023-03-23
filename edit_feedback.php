<?php

include "connect_mis_support.php";

?> 
 <div class="modal" id="myModal" >
    <div class="modal-dialog modal-xl">
     <div class="modal-content">
      
        
        <div class="modal-header">
         <h4 class="modal-title">Update Revision Ticket</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
<form onsubmit="return confirm('Are you sure you want to pending?');" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post" >



  <div class="form-group"><?php echo "<input type=\"hidden\"   name=\"rev_id\"  value=\"$_POST[id]\"   />"; ?>
    <label for="exampleFormControlTextarea1">Remarks</label>
    <textarea id="exampleFormControlTextarea1" maxlength="10485760" class="form-control" rows="10" cols="150"   name="remarks"  ></textarea>
  </div>
<button type="submit" name="SUBMIT_REVISION" class="btn btn-primary">Submit</button>


</form>          

        

  
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
 </div>
 <?php 
//}
//echo create_modal();

?>