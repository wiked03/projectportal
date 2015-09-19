<?
// =========================================================================================
// XMLHttpRequest Active Search Handler
// =========================================================================================
global $LANG;

//error_log(var_export($_POST, true));

$q_str = CORE_decode( $_POST['value'], F_URI );

// strip out trailing and leading whitespace
//$q_str = preg_replace( array('/^[\s]*/', '/[\s]*$/'), '', $q_str );

$json_obj = array();

/*
if( $USER->get('level')<=2 )
{
  $hide_where  = " AND c.fk_created_by_user=".$USER->get('id');
  $hide_where2 = "WHERE c.fk_created_by_user=".$USER->get('id');
}*/


//----------------
// pre-defined queries
switch( $_POST['query'] )
{
  case( 'users' ):
    $sql[] = "SELECT pk_id AS id, username AS val
            FROM users
            WHERE username LIKE '".CORE_encode( $q_str, F_SQL2 )."%'
            ORDER BY username ASC";
    $fields[] = array( "id", "val" );
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

    $sql[] = "SELECT pk_id AS id, name AS val
            FROM organizations
            WHERE name LIKE '%".CORE_encode( $q_str, F_SQL2 )."%' 
              ".$not_in."
            ORDER BY name ASC LIMIT 5";
    $fields[] = array( "id", "val" );
    break;

  case( 'projects' ):
    $not_in = '';
    if( $_POST['not_in'] )
      $not_in = ' AND pk_id NOT IN ( '.$_POST['not_in'].' ) ';

    if( preg_match_all( '/[\s]*(.*?);[\s]*/', $q_str, $matches ) )
    {
      for( $i = 0; $matches[1][$i]; $i++ )
        $not_in .= " AND name NOT LIKE '".CORE_encode( $matches[1][$i], F_SQL2 )."'";
    }

    $q_str = preg_replace( '/(.*;[\s]*)/', '', $q_str );

    $sql[] = "SELECT pk_id AS id, name AS val
            FROM projects
            WHERE name LIKE '%".CORE_encode( $q_str, F_SQL2 )."%' 
              ".$not_in."
            ORDER BY name ASC LIMIT 5";
    $fields[] = array( "id", "val" );
    break;

  case( 'contacts' ):

    if( preg_match( '/^[\s]*((?:[a-zA-Z]{3}\-?)?([\d]{3,})(?:\-([\d]*))?)[\s]*$/', $q_str, $matches ) )
    {
      $sql[] = "SELECT c.pk_id AS id, CONCAT_WS( ' ', c.first_name, c.last_name ) AS val, 'contact' AS class_name, o.name AS extra
                FROM contacts AS c
                  LEFT JOIN contact_orgs AS co ON co.fk_contact_id=c.pk_id
                  LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
                WHERE c.pk_id=".$matches[2]." ".$hide_where."
                GROUP BY id
                ORDER BY val ASC LIMIT 6";

      $fields[] = array( "id", "val", "class_name", "extra" );
    }
    else
    {
      $sql[] = "SELECT c.pk_id AS id, CONCAT_WS( ' ', c.first_name, c.last_name ) AS val, 'contact' AS class_name, o.name AS extra
                FROM contacts AS c
                  LEFT JOIN contact_orgs AS co ON co.fk_contact_id=c.pk_id
                  LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
                ".$hide_where2."
                GROUP BY id
                HAVING val LIKE '%".str_replace( ' ', '%', CORE_encode( $q_str, F_SQL2 ) )."%' 
                ORDER BY val ASC LIMIT 6";
      $fields[] = array( "id", "val", "class_name", "extra" );

    }
    break;


  case( 'all' ):


    if( preg_match( '/^[\s]*((?:[a-zA-Z]{3}\-?)?([\d]{3,})(?:\-([\d]*))?)[\s]*$/', $q_str, $matches ) )
    {
      if( $matches[3] != '' )
      {
        $sql[] = "SELECT i.pk_id AS id, CONCAT_WS( '-', IF(i.fk_contact_id<1000, LPAD(i.fk_contact_id, 3, '0'), i.fk_contact_id), i.int_number ) AS val, 'interview' AS class_name, c.type AS type
              FROM interviews AS i
                LEFT JOIN contacts AS c ON c.pk_id=i.fk_contact_id
                LEFT JOIN contact_orgs AS co ON co.fk_contact_id=c.pk_id
                LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
              WHERE i.fk_contact_id=".$matches[2]." AND i.int_number=".$matches[3]."
                AND NOT i.is_activity 
              GROUP BY id
              ORDER BY val ASC LIMIT 6";

        $fields[] = array( "id", "val", "class_name", "type" );
      }
      else
      {
        $sql[] = "SELECT c.pk_id AS id, CONCAT_WS( ' ', c.first_name, c.last_name ) AS val, 'contact' AS class_name, o.name AS extra
              FROM contacts AS c
                LEFT JOIN contact_orgs AS co ON co.fk_contact_id=c.pk_id
                LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
              WHERE c.pk_id=".$matches[2]." ".$hide_where."
              GROUP BY id
              ORDER BY val ASC LIMIT 6";

        $sql[] = "SELECT i.pk_id AS id, CONCAT_WS( '-', IF(i.fk_contact_id<1000, LPAD(i.fk_contact_id, 3, '0'), i.fk_contact_id), i.int_number ) AS val, 'interview' AS class_name, c.type AS type
              FROM interviews AS i
                LEFT JOIN contacts AS c ON c.pk_id=i.fk_contact_id
                LEFT JOIN contact_orgs AS co ON co.fk_contact_id=c.pk_id
                LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
              WHERE i.fk_contact_id=".$matches[2]."
                AND NOT i.is_activity
              GROUP BY id
              ORDER BY val ASC LIMIT 6";

        $fields[] = array( "id", "val", "class_name", "extra" );
        $fields[] = array( "id", "val", "class_name", "type" );
      }
    }
    else
    {


    $sql[] = "SELECT c.pk_id AS id, CONCAT_WS( ' ', c.first_name, c.last_name ) AS val, 'contact' AS class_name, o.name AS extra
            FROM contacts AS c
              LEFT JOIN contact_orgs AS co ON co.fk_contact_id=c.pk_id
              LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
            ".$hide_where2."
            GROUP BY id 
            HAVING val LIKE '%".str_replace( ' ', '%', CORE_encode( $q_str, F_SQL2 ) )."%' 
            ORDER BY val ASC LIMIT 6";

    $sql[] = "SELECT o.pk_id AS id, o.name AS val, 'org' AS class_name
            FROM organizations AS o
            WHERE o.name LIKE '%".CORE_encode( $q_str, F_SQL2 )."%'
            ORDER BY val ASC LIMIT 6";

    $sql[] = "SELECT p.pk_id AS id, p.name AS val, 'proj' AS class_name, o.name AS extra
            FROM projects AS p
              LEFT JOIN organizations AS o ON o.pk_id=p.fk_client_id
            WHERE p.name LIKE '%".CORE_encode( $q_str, F_SQL2 )."%'
            ORDER BY val ASC LIMIT 6";


    $fields[] = array( "id", "val", "class_name", "extra" );
    $fields[] = array( "id", "val", "class_name" );
    $fields[] = array( "id", "val", "class_name", "extra" );

    }
    break;

}


$json_obj['values'] = array( );
$json_obj['string'] = CORE_encode( $q_str, F_HTM );
$json_obj['string_raw'] = $q_str;

if( $q_str )
{
  foreach( $sql as $key => $value )
  {
    // run the query
    //error_log($sql[$key]);
    $result = mysql_query( $sql[$key] );

    while( $res = mysql_fetch_assoc( $result ) )
    {
      $ret_val = array( );

      // create json pair for each field:value combination
      foreach( $fields[$key] as $field )
      {
        $ret_val[ $field ] = CORE_encode( $res[ $field ], F_HTM );
        if( $field == 'type' )
          $ret_val['val'] = $LANG['source_types_short'][$res['type']].'-'.$ret_val['val'];
      }

      $ret_val['raw'] = $res[ 'val' ];

      $json_obj['values'][] = $ret_val;
    }

  }
  $json_obj['sql'] = $sql;
}

echo json_encode( $json_obj );

?>
