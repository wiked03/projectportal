<?

class CORE_DB
{
  var $name;
  var $user;
  var $passwd;
  var $host;

  function CORE_DB ( $db_name=NULL, $autoinit=true )
  {
    include( PATH_APP.'config/database'.EXT );

    if( isset( $db_name ) )
      $active_db = $db_name;

    $params = $db[$active_db];

    if (is_array($params))
    {
      foreach ($params as $key => $val)
        $this->$key = $val;
    }

    if( $autoinit )
      $this->initialize( );
  }

  function initialize( )
  {
    // attempt to connect to database host
    if( !$db = mysql_connect( $this->host, $this->user, $this->passwd ) ) 
      show_error( "Unable to connect to the database." );
  
    // attempt to select database  
    if( !mysql_select_db( $this->name, $db ) )
    {
      show_error( "Unable to find database ".$this->name."." );
    }

  }
}


//=========================================================================================
// DATABASE ACCESS FUNCTIONS
// ----------------------------------------------------------------------------------------
function sql_open_db()
{ 
  global $DB;
  
  // attempt to connect to database host
  if( !$db = mysql_connect( $DB['host'], $DB['user'], $DB['passwd'] ) ) 
    show_error( "Unable to connect to the database." );
  
  // attempt to select database  
  if( !mysql_select_db( $DB['name'], $db ) )
  {
    // expire the db cookie if select db fails
    setcookie( $DB['cookiename'], $DB['name'], time() - 3600,
            $SESSION['path'], $SESSION['domain'], $SESSION['secure'] );
    show_error( "Unable to find database ".$DB['name']."." );
  }
}

// ----------------------------------------------------------------------------------------
function jsql_query( $query )
{ 
  global $DEBUG;
  if( !$result = mysql_query( $query ) )
  {  
     if( $DEBUG )
       exit( "There was an error performing your query in the database, sorry.".N.$query.N.mysql_errno().': '.mysql_error() );
     return false; 
  }
  return $result;
}

// ----------------------------------------------------------------------------------------
// generates a mysql SELECT query given a table name, list of fields, and values
// returns the results
function jsql_select( $table, $select_field_list=NULL, $where_field_list=NULL, $value_list=NULL )
{
  $query_str = "SELECT ";
  
  if( isset( $select_field_list ) )
  {
    if( gettype( $select_field_list ) == 'array' )
    {
      for( $i = 0; isset( $select_field_list[$i] ); $i++ )
      {
        if( $i )  $query_str .= ", ";
        $query_str .= $select_field_list[$i];
      }
    }
    else
      $query_str .= $select_field_list;
  }
  else
    $query_str .= "*";
  
  $query_str .= " FROM ".$table;
  
  // print list of where conditions
  if( isset( $where_field_list ) )
  {
    $query_str .= " WHERE ";
    if( gettype( $where_field_list ) == 'array' )
    {
      for( $i = 0; isset( $where_field_list[$i] ); $i++ )
      {
        if( $i )  $query_str .= " AND ";
        $query_str .= $where_field_list[$i]."=";
        if( isset( $value_list[$i] ) )
          $query_str .= "'".$value_list[$i]."'";
        else
          $query_str .= "NULL";
      }
    }
    else
    {
      if( isset( $value_list ) )
        $query_str .= $where_field_list."='".$value_list."'";
      else
        $query_str .= $where_field_list."=NULL";
    }
  }
   
  $result = jsql_query( $query_str );
  return $result;
}



// ----------------------------------------------------------------------------------------
// generates a mysql INSERT query given a table name, list of fields, and values
// returns the INSERT_ID
function jsql_insert( $table, $field_list, $value_list )
{
  $query_str = "INSERT INTO ".$table." (";
  
  // print list of fields
  if( gettype( $field_list ) == 'array' )
  {
    for( $i = 0; isset( $field_list[$i] ); $i++ )
    {
      if( $i )  $query_str .= ", ";
      $query_str .= $field_list[$i];
    }
  }
  else
    $query_str .= $field_list;
   
  $query_str .= ") VALUES (";
  
  // print list of values
  if( gettype( $field_list ) == 'array' )
  {
    for( $i = 0; isset( $field_list[$i] ); $i++ )
    {
      if( $i)  $query_str .=  ", ";
    
      if( isset( $value_list[$i] ) )
        $query_str .= "'".$value_list[$i]."'";
      else
        $query_str .= "NULL";
    } 
  }
  else
  {
    if( isset( $value_list ) )
      $query_str .= "'".$value_list."'";
    else
      $query_str .= "NULL";
  }
  $query_str .= ")";

  //---------- 
  if( jsql_query( $query_str ) )
    return mysql_insert_id();
  else
    return false;
}

// ----------------------------------------------------------------------------------------
// generates a mysql UPDARE query given a table name, list of fields, and values
// returns true on success
function jsql_update( $table, $field_list, $value_list, $key_field_list, $key_value_list )
{
  $query_str = "UPDATE ".$table." SET ";
  
  // print list of fields 
  if( gettype( $field_list ) == 'array' )
  {
    for( $i = 0; isset($field_list[$i]); $i++ )
    {
      if( $i )
        $query_str .= ", ";
      
      if( isset( $value_list[$i] ) )
        $query_str .= $field_list[$i]."='".$value_list[$i]."'";
      else
        $query_str .= $field_list[$i]."=NULL";
    }
  }
  else
  {
    if( isset( $value_list ) )
      $query_str .= $field_list."='".$value_list."'";
    else
      $query_str .= $field_list."=NULL";
  }
  
  $query_str .= " WHERE ";
  if( gettype( $key_field_list ) == 'array' )
  {
    for($i = 0; isset($key_field_list[$i]); $i++)
    {
      if( $i )
        $query_str .=  " AND ";

      if( isset( $key_value_list[$i] ) )
        $query_str .= $key_field_list[$i]."='".$key_value_list[$i]."'";
      else
        $query_str .= $key_field_list[$i]."=NULL";
    }
  }
  else
  {
    if( isset( $key_value_list ) )
      $query_str .= $key_field_list."='".$key_value_list."'";
    else
      $query_str .= $key_field_list."=NULL";
  }
  
  //---------- 
  return ( jsql_query( $query_str ) );
}

//=========================================================================================
// LIST -> ARRAY FUNCTIONS
// ----------------------------------------------------------------------------------------
// Make a semicolon-separated list into an array
function mk_array( $list, $separator=";" )
{
  $list_2 = explode( $separator, $list );
  
  for($i = 1; isset( $list_2[($i + 1)] ); $i++)
    $array[($i - 1)] = $list_2[$i];
        
  return $array;
}

// ----------------------------------------------------------------------------------------
// Make an array into a semicolon-separated list
function mk_list ( $array, $separator=";" )
{

  $list = $separator;
  for($i = 0; isset($array[$i]); $i++)
      $list .= $array[$i].$separator;

  //if( $list == $separator )
  //return "";
  
  // $list .= "0".$separator;
  return $list;
}

// ----------------------------------------------------------------------------------------
// Make an array from a list of sorted values (eg. 4_2_3)
// Index naming convention: Type _ Sorted Order _ ID
// Spacers are: Type _ 0 _ 0
function mk_array2 ( $list, $sep1=";", $sep2="_")
{
  $list_2 = explode( $sep1, $list );
  $j = 0;
  for($i = 0; $list_2[$i]; $i++)
  {
    $list_3 = explode($sep2, $list_2[$i]);
    if( $list_3[2] )
      $array[ $j++ ] = $list_3[2];
  }
  
  return $array;
}
