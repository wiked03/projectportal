<?

class CORE_Redirect 
{
  var $dir     = '';
  var $class   = '';
  var $method  = '';
  var $vars    = array();
  var $is_page = false;
  var $default_controller;
  var $found   = false;

  //--------------------------------------
  function CORE_Redirect( $default_controller='home' )
  {
    $this->default_controller = $default_controller;

    // look for controller
    $found = $this->_CORE_find_controller( PATH_APP.'controllers/' );
    if( !$found )
    {
      $this->dir     = '';
      $this->class   = '';
      $this->method  = '';
      unset( $this->vars );

      $found = $this->_CORE_find_controller( 'pages/' );
      $this->is_page = $found;
    }

    $this->found = $found;
  }

  //--------------------------------------
  function _CORE_find_controller( $c_path, $get_method=false )
  {
    $found_file = false;
    $found_dir  = false;
    $get_vars   = false;

    // parse the path info
    $bad    = array(    '$',    '(',    ')',  '%28',  '%29');
    $good   = array('&#36;','&#40;','&#41;','&#40;','&#41;');

    $regex = '!^/(.*?)/?$!';
    preg_match( $regex, $_SERVER['PATH_INFO'], $matches );

    $params = explode( "/", $matches[1] );

    //--------------------------------------
    // check for controller
    $last_dir = NULL;

    // loop through each parameter
    foreach( $params as $param )
    {
      // 1. look for directory
      if( is_dir( $c_path.$this->dir.$param ) && !$found_file && !$get_vars )
      {
        $this->dir .= $param."/";
        $found_dir  = true;
        $last_dir   = $param;
      }
      // 2. look for file
      elseif( is_file( $c_path.$this->dir.$param.EXT )  && !$found_file  && !$get_vars )
      {
        $this->class = $param;
        $found_file  = true;
      }
      // 3. collect parameters
      else
      {
        if( !$get_vars && $get_method )
        {
          // if they typed in index, let's clean it up
          if( $param == 'index' )
          {
            header( "Location: ".PATH_WEB.$RTR->dir.$RTR->class );
            exit();
          }
          $this->method = '/'.$param;
        }
        else
          $this->vars[] = $param;
        $get_vars = true;
      }
    }
    
    if( $get_method && $found_file && !get_vars )
      $this->method = '/index';

    // if no file found, means call to index.php or folder with no params
    //  -> send to base file.
    if( !$found_file && ( $found_dir || !$get_vars ) )
    {
      $this->class = $this->default_controller;

      if( is_file( $c_path.$this->dir.$this->class.EXT ) )
        $found_file = true;
      elseif( is_file( $c_path.$this->dir.$last_dir.EXT ) )
      {
        $this->class = $last_dir;
        $found_file = true;
      }
    }

    return $found_file;
  }

}


// clean up unused page variables
function filter_page_vars( $var_count )
{
  global $RTR;
  $vars = "";
  
  if( $var_count >= count( $RTR->vars ) )
    return;
  
  for( $i = 0; ($i < $var_count) && isset( $RTR->vars[$i] ); $i++ )
  {
    $vars .= "/".$RTR->vars[$i];
  }

  header( "Location: ".PATH_WEB.$RTR->dir.$RTR->class.$RTR->method.$vars );
  exit();
}
