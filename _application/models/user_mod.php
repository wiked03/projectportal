<?
//===========================================================================================
// Class:    User
//-------------------------------------------------------------------------------------------

class User extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function User( $id=0 )
  {
    $this->id = $id;
    $this->table_name = 'users';
    $this->fields = array( 'username', 'email', 'password', 'temp', 'reset_pwd', 'imported', 'level', 'created', 'last_login', 'active' );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    // set default data
    $this->data['temp'] = 0;
    $this->data['reset_pwd'] = 0;
    $this->data['imported'] = 0;
    $this->data['level'] = 1;
    $this->data['active'] = 1;
    $this->data['created'] = date( 'Y-m-d' );
  }

  // ----------------------------------------------------------------------------------------
  function _post_set_data( $data, $format )
  {
    // if submitted from form, convert the password
    if( $format == F_HTM )
      $this->data['password'] = md5( $this->data['password'] );

    if( !$this->data['level'] )
      $this->data['level'] = 1;
  }

  // ----------------------------------------------------------------------------------------
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
  function validate ( )
  {
    $found_user = $this->get_user_by_username( $this->get('username') );

    if( !$this->get('username') )
      $error['username'] = E_ENTRY_REQUIRED;
    elseif( !preg_match( '/^[A-Z0-9._-]+$/i', $this->get('username') ) )
      $error['username'] = E_INVALID_FORMAT;
    elseif( $found_user && ($found_user != $this->id) )
      $error['username'] = E_VALUE_NOT_UNIQUE;

    if( !$this->get('password') )
      $error['password'] = E_ENTRY_REQUIRED;

    return $error;
  }

  // ----------------------------------------------------------------------------------------
  function get_user_by_username ( $username )
  {
    $result = mysql_query("SELECT *
                          FROM ".$this->table_name."
                          WHERE username=".CORE_encode( $username, F_SQL ) );
    if(!mysql_num_rows($result))
      return false;
    
    $result = mysql_fetch_array($result, MYSQL_ASSOC);

    return $result[$this->id_name];
  }

  // ----------------------------------------------------------------------------------------
  function get_user_by_email( $email )
  {
    $sql = "SELECT *
            FROM ".$this->table_name."
            WHERE email=".CORE_encode( $email, F_SQL );

    $result = mysql_query( $sql );
    if(!mysql_num_rows($result))
      return false;
    
    $result = mysql_fetch_assoc( $result );

    return $result['pk_id'];
  }
  
  // ----------------------------------------------------------------------------------------
  function check_login_info ( $username, $password )
  {
    if( !$username || !$password )
      return E_ENTRY_REQUIRED;

    //  preg_match( '/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $username ) 
    if( preg_match( '/^[A-Z0-9._-]+$/i', $username ) )
    {
      $qry = "SELECT ".$this->id_name." 
              FROM ".$this->table_name." 
              WHERE active=1 AND username=".CORE_encode( $username, F_SQL );

      $result = mysql_query( $qry ); 
      if( !mysql_num_rows( $result ) )
        return E_USER_NO_USERNAME;
    }
    else
      return E_INVALID_FORMAT;
    
      
    $id = mysql_result( $result, 0, 0 );  
    $password = md5( $password );
    
    $result = mysql_query( "SELECT ".$this->id_name." 
                           FROM ".$this->table_name." 
                           WHERE ".$this->id_name."=".$id." AND active=1 AND password=".CORE_encode( $password, F_SQL ) );
    if( !mysql_num_rows( $result ) )
      return E_USER_WRONG_PASSWORD;

    $this->load_data( $id );

    return E_OK;
  }

  // ----------------------------------------------------------------------------------------
  function check_password ( $password )
  {
	  if(strlen($password) < 8)
	  {
	  	//password is too short
	  	return E_PWD_TOO_SHORT;
	  }

	  // Check the password for numbers. Add code to the password changing function to check the password for numbers. For example:
	  if(!preg_match("#[0-9]+#", $password))
	  {
	  	//password does not contain numbers
	  	return E_PWD_DONT_CONTAIN_NUMBERS;
	  }
	  
	  //Check the password for lower-case letters. Add code to the password changing function to check the password for lower-case letters. For example:
	  if(!preg_match("#[a-z]+#", $password))
	  {
	  	//password does not contain lower-case letters
	  	return E_PWD_DONT_CONTAIN_LOWERCASE_LETTERS;
	  }
	  
	  //Check the password for capital letters. Add code to the password changing function to check the password for capital letters. For example:
	  if(!preg_match("#[A-Z]+#", $password))
	  {
	  	//password does not contain capital letters
	  	return E_PWD_DONT_CONTAIN_UPPERCASE_LETTERS;
	  }
	  
	  return E_OK;
  }
  
}

?>
