<?

global $LANG, $REGEX, $SESS;

?>

 <div id="resource_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Contribution
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/resources/add/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">


<h3 class="underline">Period Information</h3>

<div class="left_side">
<?
if( $edit_lock )
  $form->add_validation( 'int_start_date', V_REQUIRED );

if( $edit_lock )
  $form->add_validation( 'int_end_date', V_REQUIRED );

echo $form->print_item( 'int_start_date', $edit_lock, 'date' );
echo $form->print_item( 'int_end_date', $edit_lock, 'date' );
?>

<div class="form_item" style="margin-left:2cm; font-size:small;">
<b>NOTE:</b> You can use Ctrl+1, Ctrl+2, Ctrl+3 and Ctrl+4 to select the dates for Q1, Q2, Q3 and Q4 respectively.
</div>

</div>

<div class="right_side">
<?
  $name = $form->get( 'project', F_HTM );
  $disp = '<span class="project">'.$name.'</span>';
  echo $form->print_item( 'project', 0, NULL, $disp );
?>
</div>

<h3 class="underline">User Information</h3>

<div class="full_width">
<table id="users_table" style="margin-left:2cm;">
  <thead>
    <tr>
      <td>User</td>
      <td>% Effort</td>
      <td>Notes</td>
    </tr>
  </thead>
  <tbody>
    <? for ($i=0; $i<5; $i++){ 
      $my_view = new View( );
      $users = $my_view->get_list( 'project_users_to_contribute', F_HTM, str_replace('c-', '', $id));
      if($users){
          $form->add_select( 'fk_user_id_'.$i, $users, '' );
      }
      if( $edit_lock )
        $form->add_validation( 'effort_'.$i, V_REQUIRED );
    ?>
    <tr>
      <td>
          <? echo $form->print_item( 'fk_user_id_'.$i, true, "user_dropdown", NULL, true ); ?>
      </td>
      <td class="contribution_effort">
        <div class="contribution_effort" id="f_exp-effort_<? echo $i?>_div">
          <input type="text" name="effort_<? echo $i?>" id="f_exp-effort_<? echo $i?>" value="0" style="width:50px;">
          <div class="error_div" id="f_exp-effort_<? echo $i?>_error" onmouseover="show( 'f_exp-effort_<? echo $i?>_error_popup' )" onmouseout="hide('f_exp-effort_<? echo $i?>_error_popup')">
            <div id="f_exp-effort_<? echo $i?>_error_popup" class="popup">
              <div class="spacer">&nbsp;</div>
            </div>
          </div>
        </div>
      </td>
      <td class="contribution_notes">
          <textarea name="notes_<? echo $i?>" id="f_exp-notes_<? echo $i?>" rows="1"></textarea>
      </td>
    </tr>
    <?}?>
  </tbody>
</table>
</div>

<script type="text/javascript">
<?
if( $edit_lock )
{
?>
var cal_int_start_date = new Calendar( 'cal_int_start_date', 'f_exp-int_start_date', 1 );
var cal_int_end_date = new Calendar( 'cal_int_end_date', 'f_exp-int_end_date', 1 );
<?
}
?>

//----------------------------------
var resource_form = new Validate( 'resource_form', 'f_exp', <?=$form->get_validation_json( )?> );
resource_form.back_link = "<?=$SESS->get_redirect()?>";

function add_row(){
    <?
      $my_view = new View( );
      $users = $my_view->get_list( 'project_users_to_contribute', F_HTM, str_replace('c-', '', $id));
      if($users){
          $form->add_select( 'fk_user_id_'.'XXX', $users, '' );
      }
      if( $edit_lock )
        $form->add_validation( 'effort_'.'XXX', V_REQUIRED );
    
      $user_selector = $form->print_item( 'fk_user_id_'.'XXX', true, "user_dropdown", NULL, true );
      $user_selector = str_replace("\n", "", $user_selector);
      $user_selector = str_replace("'", "\\'", $user_selector);
    ?>

    // Add the new row, setting the proper index
    var user_selector = '<td><? echo $user_selector ?></td>';
    var table = document.getElementById("users_table");
    var lastRowIndex = table.rows.length-1;

    user_column = user_selector.replace(/XXX/g, lastRowIndex);
    effort_column = '<td class="contribution_effort">'+
                    '  <div class="contribution_effort" id="f_exp-effort_'+lastRowIndex+'_div">'+
                    '    <input type="text" name="effort_'+lastRowIndex+'" id="f_exp-effort_'+lastRowIndex+'" value="0" style="width:50px;">'+
                    '    <div class="error_div" id="f_exp-effort_'+lastRowIndex+'_error" onmouseover="show( \'f_exp-effort_'+lastRowIndex+'_error_popup\' )" onmouseout="hide(\'f_exp-effort_'+lastRowIndex+'_error_popup\')">'+
                    '      <div id="f_exp-effort_'+lastRowIndex+'_error_popup" class="popup">'+
                    '        <div class="spacer">&nbsp;</div>'+
                    '      </div>'+
                    '    </div>'+
                    '  </div>'+
                    '</td>'
    notes_column = '<td class="contribution_notes"><textarea name="notes_'+lastRowIndex+'" id="f_exp-notes_'+lastRowIndex+'" rows="1"></textarea></td>'

    $('#users_table').append('<tr>'+user_column+effort_column+notes_column+'</tr>');
}

var isCtrl = false;

$(document).keyup(function (e) {
	if(e.which == 17) isCtrl=false;
}).keydown(function (e) {
	if(e.which == 17) isCtrl=true;
	if(e.which == 49 && isCtrl == true) {
		//Q1
    var year = new Date().getFullYear() 
    $('#f_exp-int_start_date').val("1/1/"+year);
    $('#f_exp-int_end_date').val("3/31/"+year);
		return false;
	}
	if(e.which == 50 && isCtrl == true) {
		//Q2
    var year = new Date().getFullYear() 
    $('#f_exp-int_start_date').val("4/1/"+year);
    $('#f_exp-int_end_date').val("6/30/"+year);
		return false;
	}
	if(e.which == 51 && isCtrl == true) {
		//Q3
    var year = new Date().getFullYear() 
    $('#f_exp-int_start_date').val("7/1/"+year);
    $('#f_exp-int_end_date').val("9/30/"+year);
		return false;
	}
	if(e.which == 52 && isCtrl == true) {
		//Q4
    var year = new Date().getFullYear() 
    $('#f_exp-int_start_date').val("10/1/"+year);
    $('#f_exp-int_end_date').val("12/31/"+year);
		return false;
	}
});

</script>
  <div style="margin-left:2cm; margin-top:5px;">
  <a href="javascript:add_row()" class="button_small"><span>Add Row</span></a>
  </div>
<br>

   <div class="buttons">
    <a href="javascript:resource_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:resource_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
