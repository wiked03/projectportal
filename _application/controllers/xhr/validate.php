<?
// =========================================================================================
// XMLHttpRequest Active Search Handler
// =========================================================================================

$q_str = CORE_decode( $_POST['value'], F_URI );

// strip out trailing and leading whitespace
//$q_str = preg_replace( array('/^[\s]*/', '/[\s]*$/'), '', $q_str );

$json_obj = array();

//----------------
// pre-defined queries
switch( $_POST['query'] )
{
  case( 'users' ):
    $sql = "SELECT pk_id AS id, username AS val
            FROM users
            WHERE username LIKE '".CORE_encode( $q_str, F_SQL2 )."%'
            ORDER BY username ASC";
    $fields = array( "id", "val" );
    break;

  case( 'organizations' ):
    $not_in = '';
    if( $_POST['not_in'] )
      $not_in = ' AND pk_id NOT IN ( '.$_POST['not_in'].' ) ';

    

    if( preg_match_all( '/[\s]*(.*?);[\s]*/', $q_str, $matches ) )
    {
      for( $i = 0; $matches[1][$i]; $i++ )
        $not_in .= " AND name NOT LIKE '".CORE_encode( $matches[1][$i], F_SQL2 )."'";
    }

    $q_str = preg_replace( '/(.*;[\s]*)/', '', $q_str );

    $sql = "SELECT pk_id AS id, name AS val
            FROM organizations
            WHERE name LIKE '%".CORE_encode( $q_str, F_SQL2 )."%' 
              ".$not_in."
            ORDER BY name ASC LIMIT 5";
    $fields = array( "id", "val" );
    break;

}


$json_obj['values'] = array( );
$json_obj['string'] = CORE_encode( $q_str, F_HTM );
$json_obj['string_raw'] = $q_str;

if( $q_str )
{
  // run the query
  $result = mysql_query( $sql );

  while( $res = mysql_fetch_assoc( $result ) )
  {
    $ret_val = array( );

    // create json pair for each field:value combination
    foreach( $fields as $field )
      $ret_val[ $field ] = CORE_encode( $res[ $field ], F_HTM );

    $ret_val['raw'] = $res[ 'val' ];

    $json_obj['values'][] = $ret_val;
  }

  $json_obj['sql'] = $sql;
}

echo json_encode( $json_obj );

?>