<?

// Login script
// ---------------------------------------------------

if( $_POST['submit_form'] )
{

  // check user login info
  $PAGE->set_data( $_POST, F_HTM );

  $err_code = $USER->check_login_info( $PAGE->get('username'), $PAGE->get('password') );

  // if no error, login and redirect to previous page
  if( $err_code == E_OK )
  {
    $SESS->login( $USER->get('id'), $PAGE->get('remember') );

    // if the user currently has a temporary password, redirect them to change it
    if( $USER->get( 'temp' ) )
    {
      header('Location: '.PATH_BASE.'user/change_password');
      exit( );
    }
    
    // Check if we need to reset the password
    if( $USER->get( 'reset_pwd' ) )
    {
    	header('Location: '.PATH_BASE.'user/change_password');
    	exit( );
    }
    
    // Create login log record
    $sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (1, ".$USER->get('id').", NOW())";
    mysql_query( $sql );
    
    $SESS->redirect( );
  }

  // handle errors
  switch( $err_code )
  {
    case( E_ENTRY_REQUIRED ):
      $view_data['err_msg'] = "Please enter Username and Password to login.";
      $PAGE->set_class( 'username', 'error' );
      $PAGE->set_class( 'password', 'error' );
      break;
    case( E_USER_NO_EMAIL ):
    case( E_USER_NO_USERNAME ):
    case( E_INVALID_FORMAT ):
      $view_data['err_msg'] = translate_error( $err_code, 'Username' );
      $PAGE->set_class( 'username', 'error' );
      break;
    case( E_USER_WRONG_PASSWORD ):
      $view_data['err_msg'] = translate_error( $err_code, '' );
      $PAGE->set_class( 'password', 'error' );
  }
}

//-------------------------------------------------------------------------------------------
// Login Page
//-------------------------------------------------------------------------------------------
filter_page_vars( 0 );

$PAGE->title        = 'Login';
$PAGE->type         = 'login';
$PAGE->add_style( 'style_forms' );
$PAGE->add_script( 'jquery-1.9.0.js' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'login_form', $view_data );

//------------------------------------------------------------
?>
 </div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
