<?php
/* I used PHP becase i need more functions, this only a example how
 * to creat and call a modal after click on a link.
*/
//$sql_history = pg_query($db, "SELECT * FROM feedback_history WHERE feedback_id ='PENDING';") or die ("Could not match data because ".pg_last_error()); 
//$num_pending=pg_num_rows($sql_pending);
 
function create_modal($modal_ID = 'myModal')
{
    return "<div class='modal fade' id='$modal_ID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>                      
                    </div>
                    <div class='modal-body'>
                       $_POST[name]
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                      <button type='button' class='btn btn-primary'>Save changes</button>
                    </div>
                  </div>
                </div>
              </div>";
}
echo create_modal();
?>