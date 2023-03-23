<?php
include "user_session.php";
?>
<!DOCTYPE html>
<html>
<head>
<?php
include "scriptlink.php";
function RemoveSpecialChar($str) {
 
  // Using str_replace() function
  // to replace the word
  $res = str_replace( array( '\'', '"' ), ' ', $str);

  // Returning the result
  return $res;
  }

  $sql_feedbackloc = pg_query($db, "select * from feedback_location order by id;") or die ("Could not match data because ".pg_last_error());

  $sql_feedtype = pg_query($db, "select * from feedback_type order by id;") or die ("Could not match data because ".pg_last_error());

  $sql_unit = pg_query($db, "select * from unit order by unit_name;") or die ("Could not match data because ".pg_last_error());
  $sql_support = pg_query($db, "select * from support;") or die ("Could not match data because ".pg_last_error());
  $sql_employee = pg_query($db, "select * from employee where approver=TRUE order by  last_name;") or die ("Could not match data because ".pg_last_error());  
//echo "<meta http-equiv=\"refresh\" content=\"0;URL=sc_query.php\">";

if (isset($_POST['submit'])) {

  $unit=$_POST['unit']; 
  $support=$_POST['support']; 
  //$feedback=$_POST['feedback'];
  $approver_id=$_POST['approver_id']; 
  
  $fb_id=$_POST['fb_id']; 
  $loc_id=$_POST['loc_id']; 
  $required_date=$_POST['required_date']; 
  $attach=basename($_FILES["attach"]["name"]);

  if ($_POST['support']==1) {  
  $lrcs_acctno=$_POST['lrcs_acctno']; 
  $lrcs_loan=$_POST['lrcs_loan']; 
  $lrcs_ser=$_POST['lrcs_ser']; 
  $lrcs_err=$_POST['lrcs_err']; 
  $lrcs_des=RemoveSpecialChar($_POST['lrcs_des']); 
  $other_des=null;
  $portal_acctno=null; 
  $portal_opt=null; 
  $portal_des=null; 
  $ict_opt=null;  
  $ict_err=null;  
  $ict_des=null;      
  }
  elseif ($_POST['support']==2) { 
  $portal_acctno=$_POST['portal_acctno']; 
  $portal_opt=$_POST['portal_opt']; 
  $portal_des=RemoveSpecialChar($_POST['portal_des']);
  $other_des=null;
  $lrcs_acctno=null;  
  $lrcs_loan=null;  
  $lrcs_ser=null;  
  $lrcs_err=null;  
  $lrcs_des=null; 

  $ict_opt=null;  
  $ict_err=null;  
  $ict_des=null; 
  }
  elseif ($_POST['support']==3) {   
  $ict_opt=$_POST['ict_opt']; 
  $ict_err=$_POST['ict_err']; 
  $ict_des=RemoveSpecialChar($_POST['ict_des']);
  $other_des=null;
  $lrcs_acctno=null;  
  $lrcs_loan=null;  
  $lrcs_ser=null;  
  $lrcs_err=null;  
  $lrcs_des=null;
  
  $portal_acctno=null; 
  $portal_opt=null; 
  $portal_des=null; 
  } 
  elseif ($_POST['support']==4) { 
    $other_des=RemoveSpecialChar($_POST['other_des']);
  
    $ict_opt=null; 
    $ict_err=null; 
    $ict_des=null;
  
    $lrcs_acctno=null;  
    $lrcs_loan=null;  
    $lrcs_ser=null;  
    $lrcs_err=null;  
    $lrcs_des=null;
    
    $portal_acctno=null; 
    $portal_opt=null; 
    $portal_des=null; 
  }  

  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["attach"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
  //echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["attach"]["size"] > 10000000) {
  //echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "docx" && $imageFileType != "pdf"
&& $imageFileType != "gif" && $imageFileType != "ods" && $imageFileType != "doc" && $imageFileType != "xls" && $imageFileType != "txt") {
  //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $uploadOk = 0;
  //echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["attach"]["tmp_name"], $target_file)) {
    $uploadOk = 1;//echo "The file ". htmlspecialchars( basename( $_FILES["attach"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
// end attach file  


  $insertsql = pg_query($db, "insert into feedback(
    lrcs_acctno, 
    lrcs_loan, 
    lrcs_ser, 
    lrcs_err, 
    lrcs_des,  
    portal_acctno, 
    portal_opt,
    portal_des,other_des,  
    ict_opt, 
    ict_err,
    ict_des,     
    attach_,required_date,loc_id,fb_id,approver_id,employee_id,unit,support,date_create,status) 
  values(
    '$lrcs_acctno', 
    '$lrcs_loan', 
    '$lrcs_ser', 
    '$lrcs_err', 
    '$lrcs_des',  
    '$portal_acctno', 
    '$portal_opt',
    '$portal_des','$other_des',  
    '$ict_opt', 
    '$ict_err',
    '$ict_des',       
    '$attach','$required_date',$loc_id,$fb_id,$approver_id,$_SESSION[employee_id],'$unit','$support',current_timestamp,'FOR APPROVAL');") or die ("Could not match data because ".pg_last_error());
 
 
  
 } 

?>

<style>
#LRCS,#PORTAL,#ICT,#OTHERS {
 display:none;
}
</style> 
<script>
$(document).ready(function(){  
  $('#lrcs_acctno').mask('00-00000');

  $("input[type='file']").on("change", function () {
     if(this.files[0].size > 10000000) {
       alert("Please upload file less than 10 MB. Thanks!!");
       $(this).val('');
     }
     var ext = $("input[type='file']").val().split(".").pop().toLowerCase();
        if($.inArray(ext, ["doc","pdf",'docx',"jpg","jpeg","png","gif","ods","xls","xlsx"]) == -1) {
          alert('invalid extension!');
          $(this).val('');
        }
    })

    
  
  function other_req() {
      $("[name='other_des']").prop("required",true);
  }
  function other_false() {
      $("[name='other_des']").prop("required",false);
  }  
  function lrcs_req() {
      $("[name='lrcs_acctno']").prop("required",true);
      $("[name='lrcs_loan']").prop("required",true);
      $("[name='lrcs_ser']").prop("required",true);
      $("[name='lrcs_err']").prop("required",true);
      $("[name='lrcs_des']").prop("required",true);
  }
  function portal_req() {
      $("[name='portal_acctno']").prop("required",true);
      $("[name='portal_opt']").prop("required",true);

  } 
  function portal_false() {
      $("[name='portal_acctno']").prop("required",false);
      $("[name='portal_opt']").prop("required",false);

  }
  function ict_req() {
      $("[name='ict_opt']").prop("required",true);
      $("[name='ict_err']").prop("required",true);
      $("[name='ict_des']").prop("required",true);

  } 
  function ict_false() {
      $("[name='ict_opt']").prop("required",false);
      $("[name='ict_err']").prop("required",false);
      $("[name='ict_des']").prop("required",false);
  }        
  function lrcs_false() {
      $("[name='lrcs_acctno']").prop("required",false);
      $("[name='lrcs_loan']").prop("required",false);
      $("[name='lrcs_ser']").prop("required",false);
      $("[name='lrcs_err']").prop("required",false);
      $("[name='lrcs_des']").prop("required",false);      

  }  
  $("[name='support']").change(function(){
    var selectVal = $("#support option:selected").val();
    if (selectVal==1){
      $('#LRCS').show();
      $('#PORTAL').hide();
      $('#ICT').hide();
      $('#OTHERS').hide();                  
      $('#back_').show();
      $('#next_').hide();
      $("[name='submit']").show();
      lrcs_req();
      ict_false();
      portal_false();
      other_false();
    }
    else if (selectVal==2){
      $('#PORTAL').show();
      $('#LRCS').hide();
      $('#ICT').hide(); 
      $('#OTHERS').hide();                       
      $('#back_').show();
      $('#next_').hide(); 
      $("[name='submit']").show();
      lrcs_false();
      ict_false();
      portal_req();
      other_false();            
    }
    else if (selectVal==3){
      $('#ICT').show();
      $('#PORTAL').hide();
      $('#LRCS').hide();
      $('#OTHERS').hide();            
      $('#back_').show(); 
      $('#next_').hide();   
      $("[name='submit']").show();
      lrcs_false(); 
      ict_req();
      portal_false();
      other_false();                   
    }
    else if (selectVal==4){
      $('#ICT').hide();
      $('#PORTAL').hide();
      $('#LRCS').hide();      
      $('#OTHERS').show();                    
      $("[name='submit']").show();
      lrcs_false(); 
      ict_false();
      portal_false();
      other_req();                   
    }             
  }); 

  $("#back_").click(function(){
      $('#REQ').show();
      $('#LRCS').hide();
      $('#PORTAL').hide();
      $('#ICT').hide();
      $('#back_').hide();
      $('#next_').show();
      $("[name='submit']").hide();

 
  }); 
  




});
</script>
</head>
<body>
<?php
 include "navlink.php";
  /*
if ($_SESSION['employee_id']<>339 && $_SESSION['employee_id']<>291 && $_SESSION['employee_id']<>291 && $_SESSION['employee_id']<>176
 && $_SESSION['employee_id']<>404  && $_SESSION['employee_id']<>407 && $_SESSION['employee_id']<>434 && $_SESSION['employee_id']<>402
  && $_SESSION['employee_id']<>285) 
{ */
?>

 <div class="container-fluid">
  <br>
  <h2 class="text-center">Create Ticket</h2><hr>
  <br>
  
<form onsubmit="return confirm('Are you sure?');" autocomplete=off action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "post" enctype="multipart/form-data">
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Type of Feeback</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
        <select class="form-control" id="fb_id" name="fb_id" required >
            <option></option>  
       <?php		  
        while($row_feedtype = pg_fetch_assoc($sql_feedtype)) 
         { 
		  echo "<option value=$row_feedtype[id]>$row_feedtype[feedback_name]</option>";
	     }
       ?>	   
          </select>
        </div>        
      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Type of Program</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
        <select class="form-control" id="support" name="support" required >
      <option></option>  
       <?php		  
        while($row_support = pg_fetch_assoc($sql_support)) 
         { 
		  echo "<option value=$row_support[id]>$row_support[support_name]</option>";
	     }
       ?>	   
     </select>
        </div>        
      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Department</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
        <select class="form-control" id="unit" name="unit" required >
      <option></option>  
       <?php		  
        while($row_unit = pg_fetch_assoc($sql_unit)) 
         { 
		  echo "<option value=$row_unit[id]>$row_unit[unit_name]</option>";
	     }
       ?>	   
     </select>
        </div>        
      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Location</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
        <select class="form-control" id="loc_id" name="loc_id" required >
      <option></option>  
       <?php		  
        while($row_loc = pg_fetch_assoc($sql_feedbackloc)) 
         { 
		  echo "<option value=$row_loc[id]>$row_loc[location_name]</option>";
	     }
       ?>	   
     </select>
        </div>        
      </div>               
      
    </div> 
</div>
</div>
<br>

<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Required Date / Effectivity Date of Request </b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="date" name="required_date" class="form-control"  required>
          </label>
        </div>        
      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Name of Approver</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <select class="form-control" id="approver_id" name="approver_id" required >
            <option></option>  
       <?php		  
        while($row_employee = pg_fetch_assoc($sql_employee)) 
         { 
		  echo "<option value=$row_employee[id]>$row_employee[last_name],$row_employee[first_name]</option>";
	     }
       ?>	   
          </select>
        </div>        
      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Attached documents /  pictures, if needed</b> <span class="text-danger" style="font-weight:bold;"></span></div>
    <div class="card-body">



      <div class="row">        
        <div class="col-12 text-left">  
        <div class="form-group">
          <input type="file" class="form-control-file" name="attach" id="fileUpload"
          accept=".xlsx,.xls,image/*,.doc,.docx,.txt,.pdf"   >
        </div>
        </div>        
      </div> 
      <div id="image-holder"></div>       
      
    </div> 
</div>
</div>

<!-- LRCS  START -->
<div id="LRCS">
<br>  
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>LRCS - DETAILS OF  THE REQUEST/ISSUE </b> </div>
<!--
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="lrcs_detail"  size="150"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
-->    
</div>
</div>                                                                                                                               
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Concerned account no. </b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" id="lrcs_acctno" name="lrcs_acctno" maxlength="8"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>    
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Type of Loan</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
          <select class="form-control"  name="lrcs_loan"  > 
            <option value="" hidden  ></option>	
            <option value="ProductiveB"  >ProductiveB</option>
	          <option value="Providential"  >Providential</option>
	          <option value="Prov Educ"  >Prov Educ</option>	 
            <option value="Character"  >Character</option>
	          <option value="Emergency"  >Emergency</option>
	          <option value="Secured"  >Secured</option>
	          <option value="Special Prod"  >Special Prod</option>
	          <option value="Special Prov"  >Special Prov</option>
	          <option value="Rice"  >Rice</option>	 
	          <option value="Med/Consumer"  >Med/Consumer</option>
	          <option value="Other"  >Other</option>
	          <option value="N/A"  >N/A</option>	
          </select>            
<!--            <input type="text" name="lrcs_loan" maxlength="30"  class="form-control" > -->
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div> 
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Loan Series</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="lrcs_ser"  maxlength="1" size=1  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Error Message</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="lrcs_err"  size="150"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Description/explanation of the request / issue</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
            <textarea maxlength="10485760" class="form-control" rows="5" cols="150" id="lrcs_des" name="lrcs_des"  ></textarea>
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>

</div>     
<!-- END LRCS -->

<!-- PORTAL START -->
<div id="PORTAL">
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>PORTAL - DETAILS OF  THE REQUEST/ISSUE </b> </div>
<!--    
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="portal_detail"  size="150"  class="form-control" >
          </label>
        </div>

      </div>               
     
    </div> 
    --> 
</div>
</div>                                                                                                                               
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Concerned account no. </b><span class="text-danger" style="font-weight:bold;"></span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="portal_acctno" maxlength="12"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>    
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Portal specific concern</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">

      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="N/A">N/A
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Add Roles"  >Add Roles
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Change Location"   >Change Location
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Enhancement"   >Enhancement
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Forget Username"   >Forget Username
          </label>
        </div>
      </div>  
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="New Product"   >New Product
          </label>
        </div>
      </div>   
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Request Report"   >Request Report
          </label>
        </div>
      </div>    
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Reset/Forget Password"   >Reset/Forget Password
          </label>
        </div>
      </div>    
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="portal_opt" value="Update Policy"    >Update Policy
          </label>
        </div>
      </div>
      <!--                                           
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-2">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input"  name="portal_opt" id="portal_opt_other"  >Other
          </label>
        </div>
        <div class="col-9 text-left">  
          <label class="form-check-label">
            <input type="text" name="other_portal_text" class="form-control" id="other_portal_text" >
          </label>
        </div>        
      </div> 
      -->              
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Description/explanation of the request / issue</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
          <textarea maxlength="10485760" class="form-control" rows="5" cols="150" id="portal_des"  name="portal_des"  ></textarea>
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>

</div>

   
<!-- END PORTAL -->

<!-- ICT START -->
<div id="ICT">
<br>  
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>ICT - DETAILS OF  THE REQUEST/ISSUE </b> </div>
    <!--
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="portal_detail"  size="150"  class="form-control" >
          </label>
        </div>

      </div>               
     
    </div> 
      -->
</div>
</div>                                                                                                                                  
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>ICT specific concern</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">

      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Cant Open LRCS"  >Can't Open LRCS
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Doc Scanning"   >Doc Scanning
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Equipment Not Working"   >Equipment Not Working
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Virtual Machine Issue"   >Virtual Machine Issue
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Internet Connection"    >Internet Connection
          </label>
        </div>
      </div>  
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Printing Problem"    >Printing Problem
          </label>
        </div>
      </div>   
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Reset/Forget Password"   >Reset/Forget Password
          </label>
        </div>
      </div>    
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Wifi Access"    >Wifi Access
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="ict_opt" value="Other"    >Other
          </label>
        </div>
      </div>          
      <!--                                             
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-2">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input"  name="ict_opt" id="ict_opt_other"  >Other
          </label>
        </div>
        <div class="col-9 text-left">  
          <label class="form-check-label">
            <input type="text" name="other_ict_text" class="form-control" id="other_ict_text" >
          </label>
        </div>        
      </div>               
      -->
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Error Message</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="ict_err" maxlength="150" size="150"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>
<br>
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Description/explanation of the request / issue</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
          <textarea maxlength="10485760" class="form-control" rows="5" cols="150"  id="ict_des" name="ict_des"  ></textarea>
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>

</div>
   
<!-- END ICT -->
<br>
<!-- OTHER START -->
<div id="OTHERS">
<br>  
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>OTHER - DETAILS OF  THE REQUEST/ISSUE </b> </div>

</div>
</div>                                                                                                                                  
<br>

<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Description/explanation of the request / issue</b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
          <textarea maxlength="10485760" class="form-control" rows="5" cols="150"  id="other_des" name="other_des"  ></textarea>
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>

</div>
   
<!-- END OTHER -->
<br>
<div class="row" >

      <div class="col text-center">
       <!--   <a  class="btn btn-primary" id="next_">Next</a> 
          <a  class="btn btn-primary" id="back_" style="display:none;" >Back</a> -->
          <input type="submit" class="btn btn-primary" style="display:none;" name="submit">
      </div>
</div>







</form>  
<!--  
<div class="text-right">
<button type="button" class="btn btn-warning" onclick="resetTimeout();">RESET TIMEOUT</button>
</div>


  




  



 <BR><BR><BR><BR><BR><BR>
 <hr>
 <div class="text-center"><span style='font-weight:bold;font-size:12px;'>Created by: Ted Aron C. Cardona Jr. MIS Database Specialist&copy;&trade;</span></div>

-->
<?php
//}
?>

<BR><BR>
<?php
include "footer.php";
?>
</body>
</html> 
 




