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

if( !$PAGE->vars[0] )
{
  $SESS->redirect_msg( 'failure', 'No user specified.' );
}
if( !$my_user->valid )
{
  $SESS->redirect_msg( 'failure', 'That user does not exist in the system.' );
}
if( !$my_user->get( 'temp' ) )
{
  $SESS->redirect_msg( 'warning', 'User has already set a password. Account creation email was not sent.' );
}

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
$SESS->redirect_msg( 'success', 'User creation email sent to '.$my_user->get( 'email', F_HTM ).'.' );

  
?>