<?

//--------------------------------
function load_plugin( $name )
{
  require_once( PATH_CORE.'plugins/'.$name.EXT );
}

//--------------------------------
function load_lib( $name )
{
  if( is_file( PATH_CORE.'lib/'.$name.EXT ) )
    include_once( PATH_CORE.'lib/'.$name.EXT );
  elseif( is_file( PATH_APP.'lib/'.$name.EXT ) )
    include_once( PATH_APP.'lib/'.$name.EXT );
}

//--------------------------------
function load_model( $name )
{
  require_once( PATH_APP.'models/'.str_replace( '_mod', '', $name ).'_mod'.EXT );
}

//--------------------------------
function load_view( $name, $vars=array() )
{
  global $PAGE;

  $vars = object_to_array($vars);

  if( is_array( $vars ) )
  {
    extract( $vars );
  }

  include( PATH_APP.'views/'.$name.EXT );
}

// --------------------------------------------------------------------
function object_to_array($object)
{
  return (is_object($object)) ? get_object_vars($object) : $object;
}


// --------------------------------------------------------------------
function CORE_is_num( $data, $format=F_NUM_FLOAT )
{
  switch( $format )
  {
    case( F_NUM_UINT ):
      if( preg_match( '/^[0-9]+$/', $data ) )
        return true;
      break;

    case( F_NUM_INT ):
      if( preg_match( '/^-?[0-9]+$/', $data ) )
        return true;
      break;

    case( F_NUM_FLOAT ):
      if( preg_match( '/^-?[0-9,]+[\.]?[0-9]*$/', $data ) )
        return true;
      break;

    case( F_NUM_CURRENCY ):
//      if( preg_match( '/^[0-9]+$/', $data ) )
//        return true;
      break;

    case( F_NUM_PHONE ):
//      if( preg_match( '/^[0-9]+$/', $data ) )
//        return true;
      break;
  }

  return false;
}


// --------------------------------------------------------------------
function CORE_encode( $data, $type=F_PHP, $from_type=NULL )
{
  if( isset( $from_type ) )
    $data = CORE_decode( $data, $from_type );

  if( is_object( $data ) )
    return CORE_encode( get_object_vars( $data ), $type );

  if( is_array( $data ) )
  {
    foreach( $data as $key => $val )
      $ret_val[$key] = CORE_encode( $val, $type );

    return $ret_val;
  }

  if( is_array( $type ) )
  {
    foreach( $type as $i )
      $ret_val = CORE_encode( $data, $i );

    return $ret_val;
  }

  switch( $type )
  {
    case( F_URI ):
      $regex   = array( '/!/', '/\\\\/', '/\//', '/&/', '/\?/', '/"/', '/\'/', '/ /', '/\+/' );
      $replace = array( '!0',  '!1',  '!2', '!3', '!4', '!5', '!6', '!7', '!8' );

      $ret_val = rawurlencode( preg_replace( $regex, $replace, $data ) );
      break;

    case( F_HTM ):
      $ret_val = htmlspecialchars( $data );
      break;

    case( F_SQL ):
      if( is_null( $data ) )
        $ret_val = 'NULL';
      elseif( is_int( $data ) )
        $ret_val = $data;
      else
        $ret_val = "'".mysql_real_escape_string( $data )."'";
      break;

    case( F_SQL2 ):
      $ret_val = str_replace('\\\\', '\\\\\\\\', mysql_real_escape_string( $data) );
      break;

    case( F_PHP ):
    default:
      $ret_val = $data;
  }

  return $ret_val;
}

// --------------------------------------------------------------------
function CORE_decode( $data, $type=F_PHP )
{
  if( is_object( $data ) )
    return CORE_decode( get_object_vars( $data ), $type );

  if( is_array( $data ) )
  {
    foreach( $data as $key => $val )
      $ret_val[$key] = CORE_decode( $val, $type );

    return $ret_val;
  }

  if( is_array( $type ) )
  {
    foreach( $type as $i )
      $ret_val = CORE_decode( $data, $i );

    return $ret_val;
  }

  switch( $type )
  {
    case( F_URI ):
      $regex   = array( '/!1/', '/!2/', '/!3/', '/!4/', '/!5/', '/!6/', '/!7/', '/!8/', '/!0/'  );
      $replace = array( '\\\\', '/', '&', '?', '"', '\'', ' ', '+', '!' );

      $ret_val = preg_replace( $regex, $replace, $data );
      break;
    case( F_HTM ):
    case( F_SQL ):
    case( F_PHP ):
    case( F_SQL2 ):
    default:
      $ret_val = $data;
  }

  return $ret_val;
}

function CORE_phone( $value, $to=F_SQL )
{
  global $REGEX;
  
	/*
  if( preg_match( $REGEX['phone'], $value ) )
  {
    $phone = preg_replace( $REGEX['phone'], $REGEX['phone_replace'][0], $value );
    $ext   = preg_replace( $REGEX['phone'], $REGEX['phone_replace'][1], $value );
  }	*/

  switch( $to )
  {
    case( F_HTM ):
      if( $ext )
        $ext = ' x'.$ext;
      if( strlen( $phone ) == 7 )
        $value = substr( $phone, 0, 3 ).'-'.substr( $phone, 3 ).$ext;
      elseif( strlen( $phone ) == 10 )
        $value = '('.substr($phone, 0, 3).') '.substr( $phone, 3, 3 ).'-'.substr( $phone, 6, 4 ).$ext;
      break;

    case( F_SQL ):
      if( $phone )
      {
        $value = $phone;
        if( $ext )
          $value .= 'x'.$ext;
      }
      break;
  }

  return $value;
}

// --------------------------------------------------------------------
function CORE_date( $value, $to=F_DATE_SQL, $from=F_DATE_HTM )
{
  global $REGEX;
  // F_DATE_USA, F_DATE_EUR, F_DATE_INT, F_DATE_SQL, F_DATE_TIMESTAMP

  switch( $from )
  {
    case( F_DATE_SQL ):
      $m = (int) substr($value, 5, 2);
      $d = (int) substr($value, 8, 2);
      $y = (int) substr($value, 0, 4);
      break;

    case( F_DATE_HTM ):        
    case( F_DATE_USA ):
    case( F_DATE_EUR ):
    case( F_DATE_INT ):
      
      // make the year part optional if it isn't required
      //if( !$this->params['require_year'] )
        //$y_opt = '?';
      $regex = $REGEX['date'];

      $var_str = $REGEX['date_replace'];

      $order = array( F_DATE_USA, F_DATE_INT, F_DATE_EUR );  
      if( G_DATE_FORMAT == F_DATE_EUR )
        $order = array( F_DATE_EUR, F_DATE_INT, F_DATE_USA );
        
      // loop through the order until date match is found
      for( $i = 0; (($i < 3) && !$valid); $i++ )
      {
        $eval_str = preg_replace( $regex[($order[$i])], $var_str[($order[$i])], $value );
        // if a match was found, evaluate it and check the date
        if( $eval_str != $value )
        {
          eval( $eval_str );
          
          if( !$y )
            $y = date( 'Y' );
          elseif( $y < 50 )
            $y += 2000;
          elseif( $y < 100 )
            $y += 1900;
            
          $valid = checkdate( $m, $d, $y );
        }
      }
      break;
  }


  //---------------------------------
  if( !checkdate( $m, $d, $y ) )
    return NULL;

  if( G_DATE_LEADING_ZERO || $to == F_DATE_INT )
  {
    $m = sprintf( '%02d', $m );
    $d = sprintf( '%02d', $d );
  }
/*
  if( $y == 4 || $style == F_ALT)
    $y = "";
  else
  { 
    if( $G_DATE_FORMAT_OUT == F_DATE_INT )
      $y = $y.$G_DATE_SEPARATOR;
    else
      $y = $G_DATE_SEPARATOR.$y;
  }
*/
  //--------------------------------- 
  if( $to == F_DATE_HTM )
    $to = G_DATE_FORMAT;

  switch ( $to )
  {
    case( F_DATE_SQL ):
      return sprintf( '%04d-%02d-%02d', $y, $m, $d ); 
                 
    case( F_DATE_INT ):
      return $y.G_DATE_SEPARATOR.$m.G_DATE_SEPARATOR.$d;

    case( F_DATE_USA ):
      return $m.G_DATE_SEPARATOR.$d.G_DATE_SEPARATOR.$y;

    case( F_DATE_EUR ):
      return $d.G_DATE_SEPARATOR.$m.G_DATE_SEPARATOR.$y;   
  }


}

function CORE_make_links( $text )
{
  $ret_val = preg_replace( '/(http:\/\/|(www\.))([\S]+)/', '<a href="http://${2}${3}" target="_blank">${1}${3}</a>', $text );
  return $ret_val;
}

function CORE_make_date( $range, $end=0, $format=F_DATE_SQL )
{
  $today = array( "m"=>date('n'), "d"=>date('j'), "y"=>date('Y'), "w"=>date('w') );

  switch( $range )
  {
    case( 'lw' ):
      $end_date = mktime(0, 0, 0, $today['m'], $today['d']-1, $today['y'] );
      while( date('w', $end_date) != 6 )
        $end_date -= 60*60*24;
      $begin_date = $end_date - 6*(60*60*24);
      break;

    case( 'lm' ):
      $begin_date = mktime(0, 0, 0, $today['m']-1, 1, $today['y'] );
      $end_date   = mktime(0, 0, 0, $today['m'],   0, $today['y'] );
      break;

    case( 'cm' ):
      $begin_date = mktime(0, 0, 0, $today['m'],   1, $today['y'] );
      $end_date   = mktime(0, 0, 0, $today['m']+1, 0, $today['y'] );
      break;

    case( 'ly' ):
      $begin_date = mktime(0, 0, 0,  1,  1, $today['y']-1 );
      $end_date   = mktime(0, 0, 0, 12, 31, $today['y']-1 );
      break;

    case( 'ytd' ):
      $begin_date = mktime(0, 0, 0,  1,  1, $today['y'] );
      $end_date = mktime(0, 0, 0, $today['m'], $today['d'], $today['y'] );
      break;

    case( '0' ):
      return '';
  }

  if( $end )
    return CORE_date( date( 'Y-m-d', $end_date ), $format, F_DATE_SQL );
  else
    return CORE_date( date( 'Y-m-d', $begin_date ), $format, F_DATE_SQL );

}




//--------------------------------
function show_404( )
{
  global $PAGE, $USER, $SESS;

  header( "HTTP/1.1 404 Not Found" );
  include_once( 'pages/error.php' );
  exit( );
}

//--------------------------------
function show_error( $error='error' )
{
  echo $error;
  exit( );
}

//--------------------------------
function make_select_box( $list, $selected, $id, $name=NULL, $extra='', $multi=0 )
{
  if( is_null($name) )
    $name = $id;

  if( !$multi )
  {
    $ret_val = '<select id="'.$id.'" name="'.$name.'" '.$extra.'>'.n;
    foreach( $list as $key => $val )
    {
      $ret_val .= '<option value="'.$key.'"'.( $selected == $key ? ' selected' : '' ).'>'.$val.'</option>';
    }
    $ret_val .= '</select>'.n;
  }
  else
  {
    // crate side-by-side multiple select box
    if( !is_array( $selected ) )
      $selected = explode( ',', $selected );

    foreach( $list as $key => $val )
    {
      if( in_array( $key, $selected ) && $key )
      {
        $opts_2  .= '<option value="'.$key.'">'.$val.'</option>';
        $hidden .= '<input type="hidden" id="'.$name.$key.'" name="'.$name.'[]" value="'.$key.'"/>'.n;
      }
      else
        $opts_1 .= '<option value="'.$key.'">'.$val.'</option>';
    }

    $ret_val = '  <select id="'.$id.'_1" multiple size="7" '.$extra.'>'.n.$opts_1.'</select>'.n.
       '  <div>'.n.
       '   <a class="button_red" href="javascript:moveOption( \''.$id.'_1\', \''.$id.'_2\', \''.$name.'\', 1 )"><span>Add &raquo;</span></a>'.n.
       '   <a class="button_red" href="javascript:moveOption( \''.$id.'_2\', \''.$id.'_1\', \''.$name.'\', 0 )"><span>&laquo; Remove</span></a>'.n.
       '   <a class="button_red" href="javascript:moveAllOptions( \''.$id.'_2\', \''.$id.'_1\', \''.$name.'\', 0 )"><span>Clear</span></a>'.n.
       '  </div>'.n.
       '  <select id="'.$id.'_2" multiple size="7" '.$extra.'>'.n.$opts_2.'</select>'.n.
       '  <div id="'.$name.'">'.$hidden.'</div>'.SP_DIV.n;

  }

  return $ret_val;
}

//--------------------------------
function make_country_select_box( $selected, $id, $name=NULL, $extra='' )
{
  global $LANG;

  $list = array_merge( array(0=>"--- Select One ---"), $LANG['countries'] );

  $selected = strtoupper( $selected );

  return make_select_box( $list, $selected, $id, $name, $extra );
}

//--------------------------------
function make_state_select_box( $selected, $id, $name=NULL, $extra='' )
{
  global $LANG;

  $list = array_merge( array(0=>"--- Select One ---"), $LANG['states'] );

  $selected = strtoupper( $selected );

  return make_select_box( $list, $selected, $id, $name, $extra );
}

//--------------------------------
function make_specialty_select_box( $selected, $id, $name=NULL, $extra='', $multi=0 )
{
  global $LANG;

  $list = $LANG['specialties'];

  if( !preg_match("/multiple/i", $extra) && !$multi )
    $list = array_merge( array(0=>"--- Select One ---"), $list );

  return make_select_box( $list, $selected, $id, $name, $extra, $multi );
}

//--------------------------------
function make_industry_select_box( $selected, $id, $name=NULL, $extra='', $multi=0 )
{
  global $LANG;

  $list = $LANG['industries'];

  if( !preg_match("/multiple/i", $extra) && !$multi )
    $list = array_merge( array(0=>"--- Select One ---"), $list );

  return make_select_box( $list, $selected, $id, $name, $extra, $multi );
}

//--------------------------------
function is_checked( $index, $data )
{
  if( !is_array( $data ) )
    $data = explode( ',', $data );

  if( in_array( $index, $data ) && $index )
    return ' checked';

  return '';
}

//--------------------------------
function make_tag( $tags, $value=NULL )
{
  if( is_array( $tags ) )
  {
    foreach( $tags as $key => $val )
    {
      $val = implode( " ", $tags[$key] );

      if( $key === 'extra' )
        $ret_val .= ' '.$val;
      elseif( $key === 'multiple' )
        $ret_val .= ' multiple';
      else
        $ret_val .= ' '.$key.'="'.$val.'"';
    }
  }

  if( isset($value) )
    $ret_val .= ' value="'.CORE_encode( $value, F_HTM ).'"';

  return $ret_val;
}

//--------------------------------
  // E_OK
  // E_ENTRY_REQUIRED
  // E_INVALID_FORMAT
  // E_SELECTION_REQUIRED
  // E_INVALID_DATE
  // E_OUT_OF_RANGE
  // E_VALUE_NOT_UNIQUE
  // E_USER_NO_EMAIL
  // E_USER_NO_USERNAME
  // E_USER_WRONG_PASSWORD
function translate_error( $err, $label )
{
  if( $err == E_ENTRY_REQUIRED )
    $ret_val = "Please enter a value for '".$label."'.".N;
  elseif( $err == E_INVALID_FORMAT )
    $ret_val = "The value for '".$label."' is improperly formatted.".N;
  elseif( $err == E_SELECTION_REQUIRED )
    $ret_val = "Please select a value for '".$label."'.".N;
  elseif( $err == E_INVALID_DATE )
    $ret_val = "Please enter a valid date for '".$label."'.".N;
  elseif( $err == E_OUT_OF_RANGE )
    $ret_val = "The value for '".$label."' is out of range.".N;
  elseif( $err == E_VALUE_NOT_UNIQUE )
    $ret_val = "Sorry, that ".$label." is already in use. Please enter a different one.".N;
  elseif( $err == E_USER_NO_EMAIL )
    $ret_val = "There is no user with that Email Address.".N;
  elseif( $err == E_USER_NO_USERNAME )
    $ret_val = "There is no user by that name.".N;
  elseif( $err == E_USER_WRONG_PASSWORD )
    $ret_val = "The Password you have entered is incorrect.".N;

  return $ret_val;

}

function format_currency( $val) {
  	setlocale(LC_MONETARY, 'en_US');
    //return money_format('$%!i', $val);
	return number_to_money($val);
}

function number_to_money($value, $symbol = '$', $decimals = 2)
{
    return $symbol . ($value < 0 ? '-' : '') . number_format(abs($value), $decimals);
}

function currency_to_number($val){
  	$val = str_replace(",", "", $val);
  	$val = str_replace("$", "", $val);	
	return $val;
}

function endsWith($haystack, $needle)
{
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}

	return (substr($haystack, -$length) === $needle);
}