<?php
include "connect_mis_support.php";

//include "user_session.php";

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
$attach_file=basename($_FILES["attach"]["name"]);
$feedback_other=$_POST['other_feedback_text'];  
$required_date=$_POST['required_date']; 

// attach file
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["attach"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if image file is a actual image or fake image
/*
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["attach"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}
*/

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["attach"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "ods" && $imageFileType != "doc" && $imageFileType != "xls" && $imageFileType != "txt") {
  //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["attach"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
// end attach file

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
<style>
#LRCS,#PORTAL,#ICT {
 display:none;
}



</style> 
<?php


 

 // $sql_unit = pg_query($db, "select * from unit order by unit_name;") or die ("Could not match data because ".pg_last_error());
 // $sql_support = pg_query($db, "select * from support;") or die ("Could not match data because ".pg_last_error());
 $sql_requester = pg_query($db, "select * from employee order by last_name ;") or die ("Could not match data because ".pg_last_error());  

  $sql_employee = pg_query($db, "select * from employee where approver=TRUE order by last_name;") or die ("Could not match data because ".pg_last_error());  
//echo "<meta http-equiv=\"refresh\" content=\"0;URL=sc_query.php\">";
?>
<?php
include "scriptlink.php";
?>
<style>
#other {
  background: transparent;
  border: none;
  border-bottom: 1px solid #000000;
  -webkit-box-shadow: none;
  box-shadow: none;
  border-radius: 0;
}


</style>  
<script>
$(document).ready(function(){  
/*
$("#fileUpload").on('change', function () {

var imgPath = $(this)[0].value;
var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
    if (typeof (FileReader) != "undefined") {

        var image_holder = $("#image-holder");
        image_holder.empty();

        var reader = new FileReader();
        reader.onload = function (e) {
            $("<img />", {
                "src": e.target.result,"width":"200","height":"300",
                    "class": "thumb-image"
            }).appendTo(image_holder);

        }
        image_holder.show();
        reader.readAsDataURL($(this)[0].files[0]);
    } else {
        alert("This browser does not support FileReader.");
    }
} else {
    alert("Pls select only images");
}
});
*/
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
  $("[name='program_opt']").change(function(){
 // $("#next_").click(function(){
    if ($('#program_lrcs').prop("checked")==true){
      //$('#REQ').hide();
      $('#LRCS').show();
      $('#PORTAL').hide();
      $('#ICT').hide();
      $('#back_').show();
      $('#next_').hide();
      $("[name='submit']").show();
      lrcs_req();
      ict_false();
      portal_false();
    }
    else if ($('#program_portal').prop("checked")==true){
      //$('#REQ').hide();
      $('#PORTAL').show();
      $('#LRCS').hide();
      $('#ICT').hide();      
      $('#back_').show();
      $('#next_').hide(); 
      $("[name='submit']").show();
      lrcs_false();
      ict_false();
      portal_req();      
    }
    else if ($('#program_ict').prop("checked")==true){
     // $('#REQ').hide();
      $('#ICT').show();
      $('#PORTAL').hide();
      $('#LRCS').hide();      
      $('#back_').show(); 
      $('#next_').hide();   
      $("[name='submit']").show();
      lrcs_false(); 
      ict_req();
      portal_false();             
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
  


  $('#feedback_other').change(function () {
    if(this.checked) {
    		
        $('#other_feedback_text').attr('required', true);
    }
});

$('#feedback_issue').change(function () {
    if(this.checked) {
    		
        $('#other_feedback_text').attr('required', false);
    }
});

$('#feedback_request').change(function () {
    if(this.checked) {
    		
        $('#other_feedback_text').attr('required', false);
    }
});

});
</script>
</head>
<body>
<?php
/*
 include "navlink.php";
 
if (isset($_POST['submit'])) {

 $unit=$_POST['unit']; 
 $support=$_POST['support']; 
 $feedback=$_POST['feedback'];
 $approver_id=$_POST['approver_id'];  

if ( $num_approver > 0 ) {
 $insertsql = pg_query($db, "insert into feedback(approver_id,employee_id,unit,support,feedback,date_create,status,approvedby) values($approver_id,$_SESSION[employee_id],'$unit','$support','$feedback',current_timestamp,'PENDING','true');") or die ("Could not match data because ".pg_last_error());
}
else
 $insertsql = pg_query($db, "insert into feedback(approver_id,employee_id,unit,support,feedback,date_create,status) values($approver_id,$_SESSION[employee_id],'$unit','$support','$feedback',current_timestamp,'PENDING');") or die ("Could not match data because ".pg_last_error());


 
} 
*/
 /*
if ($_SESSION['employee_id']<>339 && $_SESSION['employee_id']<>291 && $_SESSION['employee_id']<>291 && $_SESSION['employee_id']<>176
 && $_SESSION['employee_id']<>404  && $_SESSION['employee_id']<>407 && $_SESSION['employee_id']<>434 && $_SESSION['employee_id']<>402
  && $_SESSION['employee_id']<>285) 
{ */
?>

 <div class="container">
  <br>
  <h2 class="text-center">CREATE&nbsp; M.I.S &nbsp;FEEDBACK FORM</h2><hr>
  <br>
  
<form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post" enctype="multipart/form-data">

<div id="REQ">
<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Type of Feeback</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">

      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="feedback_opt" id="feedback_request" value="REQUEST" required >REQUEST
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input"  name="feedback_opt" id="feedback_issue" value="ISSUE"  required>ISSUE
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-2">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input"  name="feedback_opt" id="feedback_other"  value="OTHER"  required>Other
          </label>
        </div>
        <div class="col-9 text-left">  
          <label class="form-check-label">
            <input type="text" name="other_feedback_text" class="form-control" id="other_feedback_text" >
          </label>
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
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="program_opt" id="program_lrcs" value="LRCS" required >LRCS
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input"  name="program_opt" id="program_portal" value="PORTAL"  required>PORTAL
          </label>
        </div>
      </div> 
      <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-2">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input"  name="program_opt" id="program_ict" value="ICT"  required>ICT
          </label>
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
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="AUDIT"  required >AUDIT
          </label>
        </div>
    </div> 

    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="BMG"  required >BMG
          </label>
        </div>
    </div>   
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="CKCM"  required >CKCM
          </label>
        </div>
    </div> 
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="EOM"  required >EOM
          </label>
        </div>
    </div>  
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="MDMSS"  required >MDMSS
          </label>
        </div>
    </div> 

    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="OCEO"  required >OCEO
          </label>
        </div>
    </div>   
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="OEHSS"  required >OEHSS
          </label>
        </div>
    </div> 
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="dept_opt" value="OSS"  required >OSS
          </label>
        </div>
    </div>     
         
      
    </div> 
</div>
</div>
<br>

<div class="row justify-content-center">
<div class="card" style="width:600px">
    <div class="card-header"><b>Name of Requester</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">



      <div class="row">
        

        <div class="col-12 text-left">  
        <select class="form-control" id="req_id" name="req_id" required >
            <option></option>  
       <?php		  
        while($row_requester = pg_fetch_assoc($sql_requester)) 
         { 
		  echo "<option value=$row_requester[id]>$row_requester[last_name],$row_requester[first_name]</option>";
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
    <div class="card-header"><b>Requester Location</b> <span class="text-danger" style="font-weight:bold;">*</span></div>
    <div class="card-body">

    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="loc_opt"  value="Main Office"  required >Main Office
          </label>
        </div>
    </div> 

    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="loc_opt"  value="SO - DASA" required >SO - DASA
          </label>
        </div>
    </div>   
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="loc_opt" value="SO - Las Piñas"  required >SO - Las Piñas
          </label>
        </div>
    </div> 
    
    <div class="row">
        <div class="col-1">  
        </div>        
        <div class="col-11">  
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="loc_opt" value="SO - Tanza"  required >SO - Tanza
          </label>
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
          <input type="file" class="form-control-file" name="attach" id="fileUpload"  >
        </div>
        </div>        
      </div> 
      <div id="image-holder"></div>       
      
    </div> 
</div>
</div>
</div>

<!-- LRCS -->
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
            <input type="text" name="lrcs_acctno"   class="form-control" >
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
            <input type="text" name="lrcs_loan"   class="form-control" >
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
            <input type="text" name="lrcs_ser"  size=1  class="form-control" >
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
            <input type="text" name="lrcs_des"  size="150"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>
</div>     
<!-- LRCS -->

<!-- PORTAL -->
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
    <div class="card-header"><b>Concerned account no. </b><span class="text-danger" style="font-weight:bold;">*</span> </div>
    <div class="card-body">
      <div class="row">        
        <div class="col-12 text-left">  
          <label class="form-check-label">
            <input type="text" name="portal_acctno"   class="form-control" >
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
            <input type="text" name="portal_des"  size="150"  class="form-control" >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>
</div>

   
<!-- PORTAL -->

<!-- ICT -->
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
            <input type="text" name="ict_err"  size="150"  class="form-control" >
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
            <input type="text" name="ict_des"  size="150"  class="form-control"  >
          </label>
        </div>

      </div>               
      
    </div> 
</div>
</div>
</div>
   
<!-- ICT -->


<br>

<div class="row" >

      <div class="col text-center">
       <!--   <a  class="btn btn-primary" id="next_">Next</a> 
          <a  class="btn btn-primary" id="back_" style="display:none;" >Back</a> -->
          <input type="submit" class="btn btn-primary" style="display:none;" name="submit">
      </div>
</div>

<!--

  <div class="row">
    <div class="col-sm-1">
		<button type="submit" class="btn btn-primary" name="submit">
		 SUBMIT
		</button>
		
    </div>       
  </div>

-->  
</form>  

<br>
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
</body>
</html> 
 




