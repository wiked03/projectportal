<?
$SESS->require_login( $USER, PATH_SELF );

$user_to_edit = $PAGE->vars[0];
if(!empty($user_to_edit)){
	$SESS->require_login( $USER, PATH_SELF, 5 );
} else {
	$user_to_edit = $USER->get('id');
}

load_model( 'user_info' );

$form_data = $_POST;

//--------------------------------------
if( $form_data['submit_form'] )
{
  $new_password = $form_data['new_password'];

  if( $USER->get( 'level' ) >= 5 ) {
  	$err_code = E_OK;
  } else {
	  if( $USER->get( 'temp' ) ){
	    $err_code = E_OK;
	  } else {
	    $err_code = $USER->check_login_info( $USER->get('username'), $form_data['password'] );
	  }
  }

  if( $err_code == E_OK && $new_password )
  {
    $my_user = new User( $user_to_edit );
    $my_user->load_data( );

    $imported = $my_user->get( 'imported' );
    $reset_pwd = $my_user->get( 'reset_pwd' );
    
    $ret = $my_user->check_password($new_password);
    if($ret!=E_OK){
    	$SESS->redirect_msg( 'failure', $LANG['errors'][$ret] );
    }
    
    // set the new password
    $my_user->set_data( array('password'=>$new_password,'temp'=>0,'reset_pwd'=>0,'imported'=>0), F_HTM );
    
    // save the user_info
    $my_user->write_back( );
    
    // save a success message and redirect
    if($user_to_edit==$USER->get('id')){
    	if($reset_pwd){
    		header('Location: /home');
    		exit( );
    	} else {
    		$SESS->redirect_msg( 'success', 'Your password has been changed successfully.' );
    	}
    } else {
    	$SESS->redirect_msg( 'success', 'The password has been changed successfully.' );
    }
    
  }
  // ---------------------------------------------------
  // Handle errors
  // if the password was the only thing wrong, then we are good to go
  if( $err_code )
  {
    $SESS->set_message( 'failure', 'The current password was incorrect.' );
  }
  if( !$new_password )
  {
    $SESS->set_message( 'failure', 'Please enter a new password.' );
  }
}


//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------
filter_page_vars( 1 );

$PAGE->title        = 'Change Password';
//$PAGE->type         = 'login';
$PAGE->add_style( 'style_forms' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

$my_obj = new User_info( $user_to_edit );
$my_obj->load_data( );

$view_data['user_to_edit'] = $user_to_edit;
$view_data['first_name'] = $my_obj->get( 'first_name' );
$view_data['last_name'] = $my_obj->get( 'last_name' );

if( $USER->get( 'temp' ) )
{
  $view_data['temp'] = true;
}  

load_view( 'user/change_password', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );