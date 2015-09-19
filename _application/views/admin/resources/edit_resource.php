<?

global $LANG, $REGEX, $SESS;

?>

 <div id="resource_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Contribution
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/resources/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<h3 class="underline">User Information</h3>

<div class="left_side">
<?

$my_view = new View( );
//$users = $my_view->get_list( 'all_users', F_HTM);
$users = $my_view->get_list( 'project_users_to_contribute', F_HTM, $form->data['fk_project_id']);
//echo print_r($users, True);
//echo '=========<br>';

if($form->data['fk_user_id']){
	$form->data['fk_user_id'] = 'u_' . $form->data['fk_user_id'];
}
if($form->data['fk_contractor_id']){
	$form->data['fk_contractor_id'] = 'c_' . $form->data['fk_contractor_id'];
}	

//echo print_r($form->data, True);
//echo '=========<br>';

if($users){
	if($form->data['fk_user_id'] && $form->data['fk_user_id']>'0'){
    	$form->add_select( 'fk_user_id', $users, 'User' );
    } else {
		$form->add_select( 'fk_contractor_id', $users, 'User' );   	
    }
}

if( !$edit_lock ) {

	if($form->data['fk_user_id'] && $form->data['fk_user_id']>'0'){
		echo $form->print_item( 'fk_user_id' );
	} else {
		echo $form->print_item( 'fk_contractor_id' );
	}

} else {
 
	if($form->data['fk_user_id'] && $form->data['fk_user_id']>'0'){
		echo $form->print_item( 'fk_user_id' );
	} else {
		echo $form->print_item( 'fk_contractor_id' );
	}
  
}
?>
</div>

<h3 class="underline">Contribution Information</h3>

<div class="left_side">
<?

if( $edit_lock )
  $form->add_validation( 'int_start_date', V_REQUIRED );

if( $edit_lock )
  $form->add_validation( 'int_end_date', V_REQUIRED );

if( $edit_lock )
  $form->add_validation( 'effort', V_REQUIRED );

echo $form->print_item( 'int_start_date', $edit_lock, 'date' );
echo $form->print_item( 'int_end_date', $edit_lock, 'date' );
echo $form->print_item( 'effort', $edit_lock, 'text' );


?>
</div>
<div class="right_side">
<?
  $name = $form->get( 'project', F_HTM );
  $disp = '<span class="project">'.$name.'</span>';
  echo $form->print_item( 'project', 0, NULL, $disp );

  

?>

</div>

<div class="full_width">
<?

$form->set_tag( 'notes', 'rows', 12, 1 );
echo $form->print_item( 'notes', $edit_lock, 'full' );
?>

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
</script>




   <div class="buttons">
    <a href="javascript:resource_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:resource_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
