<!DOCTYPE html>
<html lang="en">
  <head>
  <?php
   include "scriptlink.php";
   setlocale(LC_MONETARY,"en_PH.UTF-8"); 
  ?> 
    </head>
    <body>
        <script>            
            $(function(){
                $('a').click(function(){
                    var link = $('#name').attr('value');//$('#name').html(); 
                    $.ajax({
                        url : "testajaxresult.php",
                        type : "post",
                        dataType:"text",
                        data : {
                             name: link
                        },
                        success : function (a){
                            $('#result').html(a);
							$('#myModal').modal('show');
                        }
                    });
                });
            });
			
			
<?php 
/*
$.ajax({
    url: 'superman',
    type: 'POST',
    data: jQuery.param({ field1: "hello", field2 : "hello2"}) ,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    success: function (response) {
        alert(response.status);
    },
    error: function () {
        alert("error");
    }
}); 
In this case the param method formats the data to:

field1=hello&field2=hello2

*/
?>
			
        </script>
        <div id="result"></div>
        <a href="#" id="name" value="5" >I need this text for php script</a>
  </body>
</html>