<?

load_model( 'user' );

// ----------------------------------------------------------------------------------------
class CORE_Session
{
  var $domain       = '';
  var $path         = '';
  var $secure       = false;
  var $name         = '';
  var $_other_name  = 'crm_other_user';
  var $timeout      = 3600; // 3600 = 1 hr, 31536000 = 1 year
  var $long_timeout = 31536000; // one year
  var $default_page;
  var $message      = NULL;
  var $redirect     = NULL;

  // ------------------------------------------------
  function CORE_Session( $default_page='home' )
  {
    require( PATH_APP.'config/database'.EXT );

    foreach( $session as $key => $val )
      $this->$key = $val;

    $this->default_page = $default_page;
  }

  // ------------------------------------------------
  function set_redirect( $next_page=NULL )
  {  
    if( isset( $next_page ) )
      setcookie( $this->name.'_redirect', $next_page, 0, $this->path, $this->domain, $this->secure );
    else
      setcookie( $this->name.'_redirect', PATH_WEB, 0, $this->path, $this->domain, $this->secure );

    $this->redirect = $next_page;
  }

  // ------------------------------------------------
  function get_redirect( $page=NULL )
  {
    if( $page )
      return $page;

    if( $this->redirect )
      return $this->redirect;

    if( $_COOKIE[ $this->name.'_redirect'] )
      return $_COOKIE[ $this->name.'_redirect'];
    
    return $this->default_page;
  }

  // ------------------------------------------------
  function redirect( $page=NULL )
  {
    if( $_COOKIE[ $this->name.'_redirect'] )
    {
      // clear the redirect cookie
      setcookie( $this->name.'_redirect', '', time() - 3600, $this->path, $this->domain, $this->secure );
    }

    $redirect = $this->get_redirect( $page );
    header( 'Location: '.PATH_BASE.$redirect );
    exit( );
  }

  // ------------------------------------------------
  function redirect_to_login( $next_page=NULL )
  {
    $this->set_redirect( $next_page );
    
    header( 'Location: '.PATH_BASE.'login' );
    exit( );
  }

  // ------------------------------------------------
  // returns user id on success
  function get_user_id ( )
  {   
  	$another_user_id = $this->get_another_user_id();
  	if($another_user_id)
  		return $another_user_id;
  	
    // check for session cookie
    if( !isset( $_COOKIE[$this->name] ) )
      return NULL;
    
    $this->id = $_COOKIE[$this->name];

    // get user_id from session
    $sql = "SELECT fk_user_id 
            FROM sessions 
            WHERE (pk_id=".$this->id." AND (start_time + timeout ) > ".time()." AND remote_ip='".$_SERVER['REMOTE_ADDR']."' )
               OR (pk_id=".$this->id." AND (start_time + timeout ) > ".time()." AND timeout > 10000 )";

    $result = mysql_query( $sql );

    if( !mysql_num_rows($result) )
      return NULL;

    $user_id = mysql_result($result,0,0);

    // update session time
    $sql = "UPDATE sessions 
            SET start_time=".time()."
            WHERE pk_id=".$this->id;
      
    mysql_query( $sql );

    return $user_id;
  }

  //=========================================================================================
  // Session Data Stuff
  // ----------------------------------------------------------------------------------------
  function set_data( $data )
  {
    if( !$this->id )
      return false;

    $sql = "UPDATE sessions 
            SET session_data=".CORE_encode( $data, F_SQL )."
            WHERE pk_id=".$this->id;

    mysql_query( $sql );

    return true;
  }

  // ----------------------------------------------------------------------------------------
  function get_data( )
  {
    if( !$this->id )
      return NULL;

    $sql = "SELECT session_data 
            FROM sessions
            WHERE pk_id=".$this->id;

    $result = mysql_query( $sql );
    if(!mysql_num_rows($result))
      return NULL;
    
    $result = mysql_fetch_array($result, MYSQL_ASSOC);

    return CORE_decode( $result['session_data'], F_SQL );
  }

  // ----------------------------------------------------------------------------------------
  function clear_data( )
  {
    if( !$this->id )
      return false;

    $sql = "UPDATE sessions 
            SET session_data=NULL
            WHERE pk_id=".$this->id;

    mysql_query( $sql );
    return true;
  }


  //=========================================================================================
  // Login Stuff
  // ----------------------------------------------------------------------------------------
  function login ( $user_id, $remember_me=false )
  {
    if( $remember_me )
      $timeout = $this->long_timeout; // 31536000; // one year
    else
      $timeout = $this->timeout;

    // remove expired sessions
    mysql_query( "DELETE 
                 FROM sessions 
                 WHERE ((start_time + timeout) < ".time().")" );

    // create the new session
    mt_srand( (double)microtime()*1000000 );
    $this->id = mt_rand();
  
    mysql_query( "INSERT INTO sessions (pk_id, fk_user_id, start_time, timeout, remote_ip) 
                 VALUES (".$this->id.", ".$user_id.", ".time().", '".$timeout."', '".$_SERVER['REMOTE_ADDR']."')");
/*
    mysql_query( "UPDATE users
                  SET last_login='".date( 'Y-m-d', time() )."'
                  WHERE pk_id=".$user_id );
*/

    if( $remember_me )
      $cookie_time = time() + $timeout;
    else
      $cookie_time = 0;

    setcookie( $this->name, $this->id, $cookie_time, 
               $this->path, $this->domain, $this->secure );
    
  }
  
  // ----------------------------------------------------------------------------------------
  function logout ( )
  { 
    if( isset( $_COOKIE[$this->name] ) )
    {
      mysql_query( "DELETE 
                   FROM sessions 
                   WHERE pk_id=".$_COOKIE[$this->name] );
    }
    setcookie( $this->name, '' );
    
    $this->disable_act_as_another_user ( );
  }

  // ----------------------------------------------------------------------------------------
  function require_login( $user, $page=NULL, $level=0 )
  {
    if( !$user->get( 'id' ) )
      $this->redirect_to_login( $page );

    if( $user->get( 'level' ) >= $level )
      return;

    $this->set_message( array('type'=>'failure','text'=>'Sorry, you do not have access to that page.') );
    $this->redirect( );

  }

  // ------------------------------------------------
  function set_message( $type, $text=NULL )
  {     
    
    if( !is_array( $type ) )
      $message = array( 'type'=>$type, 'text'=>$text );
    else
      $message = $type;

    //$this->message = $message;
    //foreach( $message as $key => $val )
    //  setcookie( $this->name.'_message['.$key.']', $val, 0, $this->path, $this->domain, $this->secure );

    mysql_query( "UPDATE sessions 
                  SET system_msg=".CORE_encode( json_encode($message), F_SQL )."
                  WHERE pk_id=".$this->id );
  }

  // ------------------------------------------------
  function redirect_msg( $type, $text, $location=NULL )
  {
    $this->set_message( array('type'=>$type, 'text'=>$text ) );
    $this->redirect( $location );
  }

  // ------------------------------------------------
  function get_message( )
  {
    //if( $_COOKIE[ $this->name.'_message'] )
    //{
    //  $this->message = $_COOKIE[ $this->name.'_message'];

      // clear the cookie
    //  foreach( $this->message as $key => $val )
    //    setcookie( $this->name.'_message['.$key.']', '', time() - 3600, $this->path, $this->domain, $this->secure );
    //}

  	if($this->id){
  		 
  		$result = mysql_query( "SELECT system_msg
                  FROM sessions
                  WHERE pk_id=".$this->id );
  		
  		if( $res = mysql_fetch_assoc( $result ) )
  			$ret_val = $res['system_msg'];
  		
  		mysql_query( "UPDATE sessions
                  SET system_msg=NULL
                  WHERE pk_id=".$this->id );  		
  	}


    return $ret_val;
  }

  // ------------------------------------------------
  function display_message( )
  {
    if( !$this->message )
      return;

    $ret_val  = '<div id="system_msg" class="system_msg '.$this->message['type'].'">'.$this->message['text'].'</div>';

    $ret_val .= '<script type="text/javascript">addLoadEvent( "system_msg_timer=setTimeout( \'hide(\"system_msg\")\', 5000 );" );</script>';

    return $ret_val;
  }

  //=========================================================================================
  // Change User Stuff
  // ----------------------------------------------------------------------------------------
  function enable_act_as_another_user ( $user_id )
  {
  	$timeout = $this->timeout;
  	$this->id = $user_id;
  	$cookie_time = 0;  	
  	setcookie( $this->_other_name, $this->id, $cookie_time,
  	           $this->path, $this->domain, $this->secure );
 	  	
  }
  
  // ----------------------------------------------------------------------------------------
  function disable_act_as_another_user ( )
  {
  	setcookie( $this->_other_name, '' );
  }

  // ------------------------------------------------
  function get_another_user_id ( )
  {
  	// check for session cookie
  	if( !isset( $_COOKIE[$this->_other_name] ) )
  		return NULL;
  
  	$user_id = $_COOKIE[$this->_other_name];
  	return $user_id;
  }  
  

//if( $_COOKIE['CORE_next_page'] )
//  $path['redirect'] = $_COOKIE['CORE_next_page'];
//else
//  $path['redirect'] = WEB_ROOT.$PROGRAM['base_file'];

// redirect if not logged in
//if( !$USER->id && $PAGE['level'] > 0 )
//  redirect_login( );

}