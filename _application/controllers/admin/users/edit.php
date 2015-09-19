<?
$SESS->require_login( $USER, PATH_SELF, 5 );
filter_page_vars( 1 );

load_model( 'user_info' );
load_lib( 'email' );

$my_user = new User( );
$my_obj  = new User_info( );

$my_obj->load_data( $PAGE->vars[0] );
$my_user->load_data( $PAGE->vars[0] );
$my_obj->set_data( $my_user->get_data( F_PHP ), F_PHP );

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $my_user->set_data( $form_data, F_HTM );
  $my_obj->set_data( $form_data, F_HTM );

  $user_id = $my_user->write_back( );

  if( !$user_id )
  {
    $SESS->set_message( 'failure', 'A user with that username already exists.' );
  }
  else
  {    
    $my_obj->set( 'id', $user_id );
    $my_obj->write_back( );

    // if user was created, send creation email
    if( !$PAGE->vars[0] )
    {
      $password  = substr( md5( date('r', time()) ), 3, 10 ) ; // make password

      // set the new password
      $my_user->set_data( array('password'=>$password,'temp'=>1), F_HTM );

      // save the user_info
      $my_user->write_back( );

      // send the user an email
      $message['firstname'] = $my_obj->get( 'first_name' );
      $message['username'] = $my_user->get( 'username' );
      $message['password']  = $password;

      $email = new CORE_Email( $my_user->get( 'email' ) );
      $email->create_message( 3, $message );
      $email->send( );

      // save a success message and redirect
      $SESS->redirect_msg( 'success', 'User created successfully and password sent to '.$my_user->get( 'email', F_HTM ).'.' );

    }

    $SESS->redirect_msg( 'success', 'User saved successfully.' );
  }
}


$view_data['form'] = &$my_obj->form;

if( $my_obj->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $my_obj->get( 'id' );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." User";

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

load_view( 'admin/users/edit_user', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>