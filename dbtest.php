<?
$db['remote_test']['name']   = 'projectportaldb';
$db['remote_test']['user']   = 'projectportaldb';
$db['remote_test']['passwd'] = 'gn@tsuM1';
$db['remote_test']['host']   = '68.178.143.77';

$active_db = 'remote_test';

class CORE_DB
{
  var $name;
  var $user;
  var $passwd;
  var $host;

  function CORE_DB ( $db_name=NULL, $autoinit=true )
  {
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

  $DB = new CORE_DB( );
  
  $sql = "SELECT * FROM users";
  
  $result = mysql_query($sql);
  
  var_dump($result);


?>