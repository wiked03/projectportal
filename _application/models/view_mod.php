<?
//===========================================================================================
// View Functions
//-------------------------------------------------------------------------------------------
//  quick access functions to commonly used DB data

class View
{
  // ----------------------------------------------------------------------------------------
  function get( $name, $value=0, $format=F_HTM )
  {

    global $LANG, $USER;
    
    switch( $name )
    {
      case( 'org' ):
      case( 'org_name' ):
      case( 'organization' ):
        $sql = "SELECT name AS ret_val
                FROM organizations
                WHERE pk_id='".$value."'";
        break;

      case( 'project' ):
        $sql = "SELECT CONCAT(p.name, ' (', o.name, ')') AS ret_val
                FROM projects AS p
                  LEFT JOIN organizations AS o ON o.pk_id=p.fk_client_id
                WHERE p.pk_id='".$value."'";
        break;

      case( 'conference' ):
        $sql = "SELECT name AS ret_val
                FROM conferences AS p
                WHERE p.pk_id='".$value."'";
        break;
        
      case( 'project_is_active' ):
        $sql = "SELECT is_active AS ret_val
                FROM projects
                WHERE pk_id='".$value."'";
        break;

        case( 'conference_is_active' ):
        	$sql = "SELECT active AS ret_val
                FROM conferences
                WHERE pk_id='".$value."'";
        	break;
        
      case( 'user' ):
        $sql = "SELECT CONCAT_WS( ' ', first_name, last_name) AS ret_val
                FROM user_info
                WHERE pk_id='".$value."'";
        break;

      case( 'contractor' ):
        $sql = "SELECT name AS ret_val
                FROM contractors
                WHERE pk_id='".$value."'";
        break;

      case( 'contact' ):
        $sql = "SELECT CONCAT_WS( ' ', first_name, last_name) AS ret_val
                FROM contacts
                WHERE pk_id='".$value."'";
        break;

      case( 'contact_old' ):
        $sql = "SELECT IF((fk_created_by_user=".$USER->get('id')." OR ".($USER->get('level')>2 ? 1 : 0)."), CONCAT_WS( ' ', first_name, last_name), '".$LANG['source_hidden']."') AS ret_val
                FROM contacts
                WHERE pk_id='".$value."'";
        break;

      case( 'contact_type' ):
        $sql = "SELECT type AS ret_val
                FROM contacts 
                WHERE pk_id='".$value."'";
        break;

      case( 'contact_fn' ):
        $sql = "SELECT first_name AS ret_val
                FROM contacts
                WHERE pk_id='".$value."'";
        break;
      case( 'contact_ln' ):
        $sql = "SELECT last_name AS ret_val
                FROM contacts
                WHERE pk_id='".$value."'";
        break;
    }

//echo($sql);
//echo('<br>');

    $result = mysql_query( $sql );
    if( $res = mysql_fetch_assoc( $result ) )
      return CORE_encode( $res['ret_val'], $format );

    return NULL;

  }

  // ----------------------------------------------------------------------------------------
  function get_list( $name, $format=F_HTM, $extra=NULL )
  {
    global $USER;

    switch( $name )
    {
      case( 'active_users' ):
      case( 'users' ):
        if( $extra )
          $where = ' OR u.pk_id='.$extra;

        $sql = "SELECT CONCAT_WS( ' ', first_name, last_name) AS value, ui.pk_id AS id
                FROM user_info AS ui
                  LEFT JOIN users AS u ON u.pk_id=ui.pk_id
                WHERE u.active".$where."
                ORDER BY first_name, last_name";
        break;

        case( 'active_users_with_none' ):
        	if( $extra )
        		$where = ' OR u.pk_id='.$extra;
        
        	$sql = "SELECT 'None' as value, 0 as id
        			 UNION
        			SELECT * FROM (
        			  SELECT CONCAT_WS( ' ', first_name, last_name) AS value, ui.pk_id AS id
			                FROM user_info AS ui
			                  LEFT JOIN users AS u ON u.pk_id=ui.pk_id
			                WHERE u.active".$where."
			                ORDER BY first_name, last_name) AS a";
        	break;
        
      case( 'project_users' ):
        if( $extra )
          $where = ' OR up.fk_project_id='.$extra;

        $sql = "SELECT CONCAT_WS( ' ', first_name, last_name) AS value, ui.pk_id AS id
                FROM user_info AS ui
                  LEFT JOIN users AS u ON u.pk_id=ui.pk_id
                  LEFT JOIN user_projects AS up ON up.fk_user_id=ui.pk_id
                WHERE u.active".$where."
                ORDER BY first_name, last_name";
        break;

        case( 'project_conferences' ):
        	if( $extra )
        		$where = ' OR up.fk_project_id='.$extra;
        
        	$sql = "SELECT name AS value, c.pk_id AS id
                FROM conferences c
                  LEFT JOIN conference_projects AS up ON up.fk_conference_id=c.pk_id
                WHERE c.active".$where."
                ORDER BY name";
        	break;
        
      case( 'project_contractors' ):
        if( $extra )
          $where = ' AND up.fk_project_id='.$extra;

        $sql = "SELECT 'None' as value, 0 as id
        			 UNION
        			SELECT * FROM (SELECT CONCAT_WS( ' ', first_name, last_name) AS value, co.pk_id AS id
                FROM contacts AS co
                  LEFT JOIN contractor_projects AS up ON up.fk_contact_id=co.pk_id
                WHERE co.is_source = 4".$where."
                ORDER BY first_name, last_name) as a";
        break;

      case( 'all_users' ):
        $sql = "SELECT CONCAT_WS( ' ', first_name, last_name) AS value, ui.pk_id AS id
                FROM user_info AS ui
                  LEFT JOIN users AS u ON u.pk_id=ui.pk_id
                ORDER BY first_name, last_name";
        break;

      case( 'all_users_with_empty' ):
        $sql = "SELECT \"-----\" AS value, 0 as id  FROM user_info AS ui2
                  UNION 
                SELECT CONCAT_WS( ' ', first_name, last_name) AS value, ui.pk_id AS id
                                FROM user_info AS ui
                                  LEFT JOIN users AS u ON u.pk_id=ui.pk_id
                ORDER by value";
        break;

      case( 'project_users_to_contribute' ):

        if( $extra ){
          $where = ' AND up.fk_project_id='.$extra;
          $where2 = ' AND p.pk_id='.$extra;
          $where3 = ' WHERE cp.fk_project_id ='.$extra;
        }

        $sql = "SELECT \"-----\" AS value, 0 as id  FROM user_info AS ui2
                  UNION 
                SELECT CONCAT_WS( ' ', first_name, last_name) AS value, 
                       CONCAT('u_', ui.pk_id) AS id
                FROM user_info AS ui
                  LEFT JOIN users AS u ON u.pk_id=ui.pk_id
                  LEFT JOIN user_projects AS up ON up.fk_user_id=ui.pk_id
                WHERE u.active".$where."
                  UNION
                SELECT CONCAT_WS( ' ', first_name, last_name) AS value,
                       CONCAT('u_', ui.pk_id) AS id
                      FROM user_info AS ui 
                 LEFT JOIN users AS u ON u.pk_id=ui.pk_id 
                 LEFT JOIN projects AS p ON p.fk_pm_id = ui.pk_id
                WHERE u.active".$where2."
                  UNION
                SELECT CONCAT_WS( ' ', first_name, last_name) AS value, 
                       CONCAT('c_', c.pk_id) AS id
                  FROM contacts c 
                     INNER JOIN contractor_projects cp ON c.pk_id = cp.fk_contact_id".$where3."
                ORDER BY value";
        break;

      case( 'all_contacts' ):
        $sql = "SELECT CONCAT_WS(' ', first_name, last_name) AS value, pk_id AS id
                FROM contacts AS c
                ORDER BY CONCAT_WS(' ', first_name, last_name)";
        break;

      case( 'all_contractors' ):
        $sql = "SELECT CONCAT_WS(' ', first_name, last_name) AS value, pk_id AS id
                FROM contacts AS c
                WHERE is_source = 4
                ORDER BY CONCAT_WS(' ', first_name, last_name)";
        break;
        
      case( 'active_projects' ):
      case( 'projects' ):
        if( $extra )
          $where = ' OR p.pk_id IN ('.$extra.' )';

        $sql = "SELECT name AS value, p.pk_id AS id
                FROM projects AS p
                  LEFT JOIN user_projects AS up ON p.pk_id=up.fk_project_id
                  LEFT JOIN contractor_projects AS cp ON p.pk_id=cp.fk_project_id
                WHERE ( p.is_active
                  AND (".$USER->get('level').">=3 || (up.fk_user_id=".$USER->get('id')." )) )".$where.
              " ORDER BY p.name";
        break;

      case( 'all_active_projects' ):
        	$sql = "SELECT name AS value, pk_id AS id
                FROM projects AS p
        		WHERE p.is_active
                ORDER BY p.name";
        	break;
        
      case( 'all_projects' ):
        $sql = "SELECT name AS value, pk_id AS id
                FROM projects AS p
                ORDER BY p.name";
        break;
        	
      case( 'int_projects' ):
        if( $extra )
          $where = ' OR ip.fk_interview_id='.$extra;

        $sql = "SELECT CONCAT(p.name, ' (', o.name, ')') AS value, p.pk_id AS id
                FROM projects AS p
        		  LEFT JOIN user_projects AS up ON p.pk_id=up.fk_project_id
        		  LEFT JOIN collector_projects AS collp ON p.pk_id=collp.fk_project_id
                  LEFT JOIN contractor_projects AS cp ON p.pk_id=cp.fk_project_id
                  LEFT JOIN interview_projects AS ip ON p.pk_id=ip.fk_project_id
                  LEFT JOIN organizations AS o ON p.fk_client_id=o.pk_id
                WHERE ( p.is_active
                  AND (".$USER->get('level').">=3 
                  		|| (up.fk_user_id=".$USER->get('id')." )
                  		|| (collp.fk_user_id=".$USER->get('id')." )
                  		|| (p.fk_pm_id=".$USER->get('id')." )
                  		|| (p.fk_dir_id=".$USER->get('id')." )
                  				) )".$where.
              " GROUP BY p.name, o.name, p.pk_id ORDER BY p.name";

        break;

        case( 'all_active_conferences' ):
        	$sql = "SELECT name AS value, pk_id AS id
                FROM conferences AS p
        		WHERE p.active
                ORDER BY p.name";
        	break;
        
        case( 'all_conferences' ):
        	$sql = "SELECT name AS value, pk_id AS id
            FROM conferences AS p
            ORDER BY p.name";
        	break;
    
    }

/*
echo '<br>';
echo $name;
//error_log($sql);
echo '<br>';
echo $sql;
echo '<br>';
*/
    $result = mysql_query( $sql );
    while( $res = mysql_fetch_assoc( $result ) )
    {
      // The call to CORE_encode is very expensive, and it does not seems to be necessary at all.
      //$ret_val[ $res['id'] ] = CORE_encode( $res['value'], $format );
    	$ret_val[ $res['id'] ] = $res['value'];
    }

    return $ret_val;

  }

  function on_project( $user_id, $contact_id )
  {
    
    $sql = "SELECT COUNT( DISTINCT up.fk_project_id ) AS the_count
            FROM interviews AS i
              LEFT JOIN interview_projects AS ip ON ip.fk_interview_id=i.pk_id
              LEFT JOIN user_projects AS up ON up.fk_project_id=ip.fk_project_id
              LEFT JOIN contractor_projects AS cp ON ip.fk_project_id=cp.fk_project_id
            WHERE up.fk_user_id=".$user_id." AND i.fk_contact_id=".$contact_id;

    $ret_val[] = 0;

    //echo $sql;
    $result = mysql_query( $sql );
    $res = mysql_fetch_assoc( $result );
    
    return ($res['the_count'] > 0);

  }

}
