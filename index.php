<?

// Start of code
$time = microtime(true); // Gets microseconds

// Error reporting level
error_reporting( E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED) );
ini_set('display_errors', 1);

// Core folder
$core_folder  = "_core";

// Application folder
$app_folder   = "_application";

// Default controller to load
$default_controller = 'home';

//------------------------------------------------------------------------------------------

// set PATH_ defines
define( 'PATH_CORE', $core_folder.'/' );
define( 'PATH_APP',  $app_folder.'/' );
define( 'EXT',       '.php' );

// example: http://localhost/dev/FIT/patient_reg/1
// PATH_BASE: /dev/FIT/
// PATH_WEB:  http://localhost/dev/FIT/
// PATH_SELF: patient_reg/1
define( 'PATH_BASE',  str_replace( pathinfo(__FILE__, PATHINFO_BASENAME), '', $_SERVER['SCRIPT_NAME'] ) );
define( 'PATH_WEB',  'http://'.$_SERVER['HTTP_HOST'].PATH_BASE );
define( 'PATH_SELF', substr($_SERVER['PATH_INFO'], 1) );

// includes
require_once( PATH_CORE.'lib/common'.EXT );
require_once( PATH_CORE.'lib/base_model'.EXT );
require_once( PATH_CORE.'lib/redirect'.EXT );
require_once( PATH_CORE.'lib/page'.EXT );
require( PATH_APP.'config/constants'.EXT );
include( PATH_APP.'config/autoload'.EXT );

if( $autoload['language'] )
  include( PATH_APP.'config/languages/'.$autoload['language'].EXT );

// -----------------------
$PAGE = new CORE_Page( );

// autoload stuff
foreach( $autoload['lib'] as $lib )
{
  load_lib( $lib );

  if( $lib == 'database' )
    $DB = new CORE_DB( );

  elseif( $lib == 'session' )
  {
    $SESS = new CORE_Session( $default_controller );
    $USER = new User();

    // get user login info
    $USER->load_data( $SESS->get_user_id( ) );

    // if no one logged in (not even guest), go home
    if( is_null( $SESS->get_user_id() ) )
    {
      $SESS->login( 0 );
      header( 'Location: '.PATH_BASE );
      exit( );
    }

  }
}

foreach( $autoload['model'] as $model )
  load_model( $model );

foreach( $autoload['plugin'] as $plugin )
  load_plugin( $plugin );

// Check if we need to reset the password
if (strpos($_SERVER["REQUEST_URI"], 'change_password') == false && 
		strpos($_SERVER["REQUEST_URI"], 'logout') == false && 
			strpos($_SERVER["REQUEST_URI"], 'login') == false &&
		       strpos($_SERVER["REQUEST_URI"], 'xhr') == false) {
	
	if( $USER->get( 'reset_pwd' ) )
	{
		header('Location: user/change_password');
		exit( );
	}
	
	//$SESS->redirect( );
}


// find the page and include it
$RTR = new CORE_Redirect( $default_controller );

if( !$RTR->found )
  show_404( );

if( $RTR->is_page )
  $page_path = 'pages/';
else
  $page_path = PATH_APP.'controllers/';

if( $RTR->vars )
{
  $PAGE->vars = $RTR->vars;
  $PAGE->var_string = '/'.implode( "/", $RTR->vars );
}


include( $page_path.$RTR->dir.$RTR->class.EXT );

// End of code

if (strpos($_SERVER["REQUEST_URI"], 'xhr') == false && strpos($_SERVER["REQUEST_URI"], 'export_word') == false){
	echo "<div style='font-size: 11px; color: #303030; width: 100%; height: 25px; float: left; left:50%; text-align: center;'>Time Elapsed: ".round((microtime(true) - $time),5)."s</div>";
}

exit();
//==========================================================
