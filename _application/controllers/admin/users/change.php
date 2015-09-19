<?
if (!($SESS->get_another_user_id())){
    $SESS->require_login( $USER, PATH_SELF, 5 );
}

filter_page_vars( 1 );

load_model( 'user_list' );
load_model( 'user_info' );

$my_obj  = new User_list( );

$my_obj->load_data( $PAGE->vars[0] );

$form_data = $_POST;

if( $form_data['submit_form'] )
{
	//error_log(print_r($form_data, true));
	if( $form_data['disable_another_user'] && $form_data['disable_another_user'] == '1'){
		$another_user_id = NULL;
		$SESS->disable_act_as_another_user ( );
	} else { 
		$another_user_id = $form_data['users'];
		$SESS->enable_act_as_another_user ( $another_user_id );
	}
	
	$SESS->redirect( 'home' );
	
} else {
	$another_user_id = $SESS->get_another_user_id();
}

if($another_user_id){
	$OTHER_USER = new User_info();
	$OTHER_USER->load_data( $another_user_id );
} else {
	$OTHER_USER = NULL;
}

$view_data['another_user'] = $OTHER_USER;

$view_data['form'] = &$my_obj->form;

if( $my_obj->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $my_obj->get( 'id' );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Act as a Different User";

$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );

$PAGE->no_redirect = true;
//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'admin/users/change_user', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
