<?
// =========================================================================================
// Voice of Physicians
// =========================================================================================

load_model( 'user_info' );
load_lib( 'email' );


$form_data = $_POST;

//--------------------------------------
if( $form_data['submit_form'] )
{
  $email = $form_data['email'];

  $user_id = $USER->get_user_by_email( $email );

  if( $user_id )
  {
    $password  = substr( md5( date('r', time()) ), 3, 10 ) ; // make password

    $my_user = new User( $user_id );
    $my_user->load_data( );
    // set the new password
    $my_user->set_data( array('password'=>$password,'temp'=>1), F_HTM );

    // save the user_info
    $my_user->write_back( );

    // send the user an email
    $my_info = new User_info( $user_id );
    $my_info->load_data( );

    $message['firstname'] = $my_info->get( 'first_name' );
    $message['username']  = $my_user->get( 'username' );
    $message['password']  = $password;

    $email = new CORE_Email( $my_user->get( 'email' ) );
    $email->create_message( 2, $message );
    $email->send( );

    // save a success message and redirect
    $SESS->redirect_msg( 'success', 'A new password has been sent to '.$my_user->get( 'email', F_HTM ).'.' );
  }
  // ---------------------------------------------------
  // Handle errors
  if( !$email )
    $SESS->set_message( 'failure', 'Please enter an email address.' );
  else
    $SESS->set_message( 'failure', 'No user found with that email address.' );
}



//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------
filter_page_vars( 0 );

$PAGE->title        = 'Forgot Password';
$PAGE->type         = 'login';
$PAGE->add_style( 'style_forms' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'user/forgot_password', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );