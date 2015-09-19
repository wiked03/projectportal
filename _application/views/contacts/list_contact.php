<?

// set 'any' as default
$search_data['type'] = 0;
$search_data['specialty']  = 0;
$search_data['degree'] = 0;
$search_data['is_source'] = 2;
$search_data['recontact'] = 2;
$search_data['col'] = '0.1.2.3.4.8.17';

$view = new View( );

global $LANG, $REGEX, $USER;

$var_string = CORE_decode( $var_string, F_URI );

// Override default list of columns
if( $USER->get('source_defaults') ){
	$search_data['col'] = $USER->get('source_defaults');
}


// ============================================================================
// URL line search strings

// --------------------
// (col) columns
if( preg_match( '/\/col-([\.\d]+)/', $var_string, $matches ) )
{
  $search_data['col'] = $matches[1];
}
// --------------------
// (fn) first_name
if( preg_match( '/\/fn-([^\/]+)/', $var_string, $matches ) )
{
  /*
  if( $USER->get('level')<=2 )
    $where[] = "(c.fk_created_by_user=".$USER->get('id')." AND c.first_name LIKE '%".$matches[1]."%')";
  else
    $where[] = "c.first_name LIKE '%".$matches[1]."%'";
  */
  $where[] = "c.first_name LIKE \"%".$matches[1]."%\"";

  $search_data['first_name'] = $matches[1];
  $search_string[] = '<span class="label">First Name</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (ln) last_name
if( preg_match( '/\/ln-([^\/]+)/', $var_string, $matches ) )
{
  /*
  if( $USER->get('level')<=2 )
    $where[] = "(c.fk_created_by_user=".$USER->get('id')." AND c.last_name LIKE '%".$matches[1]."%')";
  else
    $where[] = "c.last_name LIKE '%".$matches[1]."%'";
  */
  $where[] = "c.last_name LIKE \"%".$matches[1]."%\"";

  $search_data['last_name'] = $matches[1];
  $search_string[] = '<span class="label">Last Name</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (t) title
if( preg_match( '/\/t-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "c.title LIKE '%".$matches[1]."%'";

  $search_data['title'] = $matches[1];
  $search_string[] = '<span class="label">Title</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (org) org
if( preg_match( '/\/org-([\d]+)($|\/)/', $var_string, $matches ) )
{
  $where[] = "o.pk_id='".$matches[1]."'";
  $org_name = $view->get( 'org_name', $matches[1], F_PHP );

  $search_data['org_search'] = $org_name;
  $search_string[] = '<span class="label">Organization</span> is "<span class="search_param">'.CORE_encode( $org_name, F_HTM ).'</span>"';
}
elseif( preg_match( '/\/org-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['org_search'] = $matches[1];

  $str = array();
  $like = array();
  if( preg_match_all( '/[\s]*(.*?)[\s]*(;|$)/', $matches[1], $matches ) )
  {
    for( $i = 0; isset($matches[1][$i]); $i++ )
    {
      if( $matches[1][$i] )
      {
        $like[] = "o.name LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%'";
        $str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
      }
    }
  }

  $where[] = ' ( '.implode( ' OR ', $like ). '  )';
  $search_string[] = '<span class="label">Organization</span> like '.implode( ' or ', $str ); 
}
// --------------------
// (cit) city
if( preg_match( '/\/cit-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['city'] = $matches[1];

  $str = array();
  if( preg_match_all( '/[\s]*(.*?)[\s]*(;|$)/', $matches[1], $matches ) )
  {
    for( $i = 0; isset($matches[1][$i]); $i++ )
    {
      if( $matches[1][$i] )
      {
        $like[] = "co.city LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%'";
        $str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
      }
    }
  }

  $where[] = ' ( '.implode( ' OR ', $like ). '  )';
  $search_string[] = '<span class="label">City</span> like '.implode( ' or ', $str ); 
}
// --------------------
// (cit) zipcode
if( preg_match( '/\/zip-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['zipcode'] = $matches[1];

  $str = array();
  if( preg_match_all( '/[\s]*(.*?)[\s]*(;|$)/', $matches[1], $matches ) )
  {
    for( $i = 0; isset($matches[1][$i]); $i++ )
    {
      if( $matches[1][$i] )
      {
        $like[] = "co.zipcode LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%'";
        $str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
      }
    }
  }

  $where[] = ' ( '.implode( ' OR ', $like ). '  )';
  $search_string[] = '<span class="label">zipcode</span> like '.implode( ' or ', $str ); 
}
// --------------------
// (st) state
if( preg_match( '/\/st-([A-Za-z\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['states'][strtoupper($value)] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['states'][strtoupper($value)].'</span>"';
      $q_str[] = strtoupper($value);
    }
  }

  if( $q_str )
  {
    $where[] = "co.state REGEXP '(\\^|\\.)(".implode( '|', $q_str ).")($|\\.)'";

    $search_data['state'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">State</span> is '.implode( ' or ', $s_str );
  }
}
// --------------------
// (co) country
if( preg_match( '/\/co-([A-Za-z\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['countries'][strtoupper($value)] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['countries'][strtoupper($value)].'</span>"';
      $q_str[] = strtoupper($value);
    }
  }

  if( $q_str )
  {
    $where[] = "co.country REGEXP '(\\^|\\.)(".implode( '|', $q_str ).")($|\\.)'";

    $search_data['country'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Country</span> is '.implode( ' or ', $s_str );
  }

}
// --------------------
// (bg) background/notes
if( preg_match( '/\/bg-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['notes'] = $matches[1];

  $str = array();

  $outer = explode( '|', $matches[1] );
  foreach( $outer as $o => $val )
  {
    $i_str = array();
    $i_like = array();

    if( preg_match_all( '/[\s]*(.*?)[\s]*(;|\+|$)/', $val, $matches ) )
    {
      for( $i = 0; isset($matches[1][$i]); $i++ )
      {
        if( $matches[1][$i] )
        {
          $i_like[] = "( c.background LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%' ".
                       " OR c.notes LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%' )";
          $i_str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
        }
      }
    }

    $o_where[] = ' ( '.implode( ' AND ', $i_like ). '  )';
    $o_str[]  = implode( ' and ', $i_str );
  }

  $where[] = ' ( '.implode( ' OR ', $o_where ). '  )';
  $str[]  = implode( ' or ', $o_str );


  $search_string[] = '<span class="label">Background</span> or <span class="label">Notes</span> contains '.implode( ' and ', $str ); 
}
// --------------------
// (e) email
if( preg_match( '/\/e-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "( c.email1 LIKE '%".$matches[1]."%' OR c.email2 LIKE '%".$matches[1]."%' )";

  $search_data['email1'] = $matches[1];
  $search_string[] = '<span class="label">Email</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (ph) phone
if( preg_match( '/\/ph-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "( c.phone1 LIKE '%".$matches[1]."%' OR c.phone2 LIKE '%".$matches[1]."%' OR c.phone3 LIKE '%".$matches[1]."%' )";

  $search_data['phone1'] = $matches[1];
  $search_string[] = '<span class="label">Phone</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (typ) type
//if( preg_match( '/\/typ-([\d\.]+)($|\/)/', $var_string, $matches ) )
//{
//  // make sure valid
//  $vals = explode( '.', $matches[1] );
//  $q_str = NULL;
//  $s_str = '';
//
//  foreach( $vals as $value )
//  {
//    if( isset( $LANG['source_types'][$value] ) )
//    {
//      $s_str[] = '"<span class="search_param">'.$LANG['source_types'][$value].'</span>"';
//      $q_str[] = $value;
//    }
//  }
//
//  if( $q_str )
//  {
//    $where[] = "c.type IN (".implode( ',', $q_str ).") ";
//    $search_data['type1'] = implode( '.', $q_str );
//    $search_string[] = '<span class="label">Type</span> is '.implode( ' or ', $s_str );
//  }
//}
// --------------------
// (src) is_source
if( preg_match( '/\/src-([\d]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  if( isset( $LANG['contact_types'][$matches[1]] ) )
  {
    $where[] = "c.is_source='".$matches[1]."'";

    $search_data['is_source'] = $matches[1];
    $search_string[] = 'contact is a <span class="search_param">'.$LANG['contact_types'][$matches[1]].'</span>';
  }
}
// --------------------
// (rc) recontact
if( preg_match( '/\/rc-([\d]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  if( isset( $LANG['yes'][$matches[1]] ) )
  {
    $where[] = "c.recontact='".$matches[1]."'";

    $search_data['recontact'] = $matches[1];
    $search_string[] = 'contact <span class="search_param">IS'.($matches[1]?'':' NOT').'</span> open to recontact';
  }
}
// --------------------
// (ta) specialty
if( preg_match( '/\/ta-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['specialties'][$value] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['specialties'][$value].'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "c.specialty REGEXP '(\\^|\\.)(".implode( '|', $q_str ).")($|\\.)'";
//    $where[] = "c.specialty IN (".implode( ',', $q_str ).") ";
    $search_data['specialty1'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Therapeutic Area</span> is '.implode( ' or ', $s_str );
  }

}
// --------------------
// (deg) degree
if( preg_match( '/\/deg-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['degrees'][$value] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['degrees'][$value].'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "c.degree REGEXP '(\\^|\\.)(".implode( '|', $q_str ).")($|\\.)'";
    //$where[] = "c.degree IN (".implode( ',', $q_str ).") ";
    $search_data['degree1'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Degree</span> is '.implode( ' or ', $s_str );
  }
}
// --------------------
// (prl) project list
if( preg_match( '/\/prl-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $prj_name = $view->get( 'project', $value, F_PHP );
    if( $prj_name )
    {
      $s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "ip.fk_project_id IN (".implode( ',', $q_str ).") ";
    $search_data['project_list'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Project</span> is '.implode( ' or ', $s_str );
  }

}

// --------------------
// (cnf) conference list
if( preg_match( '/\/cnf-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
	// make sure valid
	$vals = explode( '.', $matches[1] );
	$q_str = NULL;
	$s_str = '';

	foreach( $vals as $value )
	{
		$prj_name = $view->get( 'conference', $value, F_PHP );
		if( $prj_name )
		{
			$s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
			$q_str[] = $value;
		}
	}

	if( $q_str )
	{
		$where[] = "icnf.fk_conference_id IN (".implode( ',', $q_str ).") ";
		$search_data['conference_list'] = implode( '.', $q_str );
		$search_string[] = '<span class="label">Conference</span> is '.implode( ' or ', $s_str );
	}

}

// --------------------
// (id) source_id
if( preg_match( '/\/id-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['source_id'] = $matches[1];

  $str = array();
  $like = array();
  if( preg_match_all( '/[\s]*((?:[a-zA-Z]{3}\-?)?([\d]{3,})(?:\-([\d]+))?)[\s]*(;|$)/', $matches[1], $matches ) )
  {
    for( $i = 0; isset($matches[2][$i]); $i++ )
    {
      if( $matches[2][$i] )
      {
        $like[] = "c.pk_id = '".CORE_encode( $matches[2][$i], F_SQL2 )."'";
        $str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
      }
    }
  }

  if( $str )
  {
    $where[] = ' ( '.implode( ' OR ', $like ). '  )';
    $search_string[] = '<span class="label">ID</span> is '.implode( ' or ', $str ); 
  }
  else
    $search_data['source_id'] = '';
}
// --------------------
// (lay) layout fields
if( preg_match( '/\/lay-([^\/]+)/', $var_string, $matches ) )
{
	$save_layout = $matches[1];
	if($save_layout=='1'){
		$layout_to_save = $search_data['col'];
		
		$sql = "UPDATE users SET source_defaults = '".
            $layout_to_save."' WHERE pk_id = " .
            $USER->get('id');
		$result = mysql_query( $sql );		
	}
}
// --------------------
// (all) all
if( preg_match( '/\/all($|\/)/', $var_string, $matches ) )
{
  $where[] = 1;
  $search_string['all'] = true;
}

// ============================================================================
// if user is analyst, lock contact name
/*
if( $USER->get('level')<=2 )
{
  $where[] = "(c.fk_created_by_user=".$USER->get('id')." OR (up.fk_user_id=".$USER->get('id').") AND c.is_source <> 3 )";
}*/

if( isset( $where ) )
{
  $where = "WHERE ".implode( " AND ", $where );
  $show_list = true;
}

$from = "FROM contacts AS c
           LEFT JOIN contact_orgs AS co ON c.pk_id=co.fk_contact_id
           LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
           LEFT JOIN interviews AS i ON c.pk_id=i.fk_contact_id
           LEFT JOIN interview_projects AS ip ON ip.fk_interview_id=i.pk_id
           LEFT JOIN user_projects AS up ON up.fk_project_id=ip.fk_project_id
           LEFT JOIN interview_conferences AS icnf ON i.pk_id=icnf.fk_interview_id
           LEFT JOIN contractor_projects AS cp ON cp.fk_project_id=ip.fk_project_id";

// ---------------------------------------------------
// get count from DB
$sql = "SELECT COUNT( DISTINCT c.pk_id ) AS total
        ".$from."
        ".$where;

//echo $sql;

$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

// ---------------------------------------------------
$list->set_total( $total );
$list->no_footer = true;

$list->add_column( 's_id',    'ID', 65, 'right' ); // 0
$list->add_column( 'first_name',   'First Name', 70 ); // 1
$list->add_column( 'last_name',    'Last Name', 90 ); // 2
$list->add_column( 'title',        'Title', 145 ); // 3
$list->add_column( 'org_name',     'Organization', 200 ); // 4

$list->add_column( 'city',     'City', 100 ); // 5
$list->add_column( 'state',     'State', 40, 'center' ); // 6
$list->add_column( 'country',     'Country', 100 ); // 7

$list->add_column( 'specialty',    'Therapeutic Area', 130 ); // 8
$list->add_column( 'degree',       'Degree', 55 ); // 9
$list->add_column( 'type',         'Type', 40, 'center' ); // 10
$list->add_column( 'is_source',    'S/L', 40 ); // 11
$list->add_column( 'recontact',    'Recontact', 58, 'center' ); // 12
$list->add_column( 'reliability',  'Reliability', 60, 'center' ); // 13
$list->add_column( 'phone',        'Phone', 105 ); // 14
$list->add_column( 'email',        'Email', 145 ); // 15

$list->add_column( 'conferences',     'Conferences', 205 ); // 16
$CONFERENCES_COL = 16;

if( !$csv && $USER->get('level') >= 5 )
  $list->add_column( 'actions',      'Actions',     100, 'center', true ); // 17
elseif( !$csv )
  $list->add_column( 'actions',      'Actions',     75, 'center', true ); // 17

//$list->required_columns( '0.1' );
$list->default_columns( $search_data['col'] );

// ---------------------------------------------------
// run query
$sql = "SELECT c.pk_id AS id, c.*, c.pk_id AS s_id, IF( (c.phone1 IS NULL OR c.phone1=''), IF((c.phone2 IS NULL OR c.phone2=''), c.phone3, c.phone2), c.phone1 ) AS phone,
           IF( (c.email1 IS NULL OR c.email1=''), c.email2, c.email1 ) AS email, COUNT( DISTINCT i.pk_id ) AS int_count, LPAD(SUBSTRING_INDEX(c.specialty, '.', 1),4,'0') AS specialty,
           CONCAT_WS( ', ',c.last_name,c.first_name) AS name, o.name AS org_name, o.pk_id AS org_id, (COUNT( DISTINCT co.fk_organization_id ) - 1) AS org_count,
           co.city AS city, co.state AS state, co.country AS country
        ".$from."
        ".$where."
        GROUP BY id
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();
/*
echo '<br>';
echo $sql;
echo '<br>';
*/
$result = mysql_query( $sql );

// ---------------------------------------------------

while( $res = mysql_fetch_assoc( $result ) )
{

  $res['s_id'] = $LANG['source_types_short'][$res['type']].'-'.sprintf( '%03d', $res['s_id'] );

  $res['type'] = $LANG['source_types_short'][ $res['type'] ];
  $res['country'] = $LANG['countries'][ $res['country'] ];

  $res['recontact'] = $LANG['yes'][$res['recontact']];

  $degree_str = array();
  $degrees = explode( '.', $res['degree'] );
  foreach( $degrees as $degree )
    $degree_str[] = $LANG['degrees'][$degree];
  $res['degree'] = implode( ', ', $degree_str );

  $val_str = array();
  $values = explode( '.', $res['specialty'] );
  foreach( $values as $value )
    $val_str[] = $LANG['specialties'][(int)$value];
  $res['specialty'] = implode( ', ', $val_str );


  $res['is_source'] = $LANG['contact_types'][ $res['is_source'] ];
  $res['reliability'] = $LANG['reliability_short'][ $res['reliability'] ];
  $res['phone'] = CORE_phone( $res['phone'], F_HTM );

  $list->add_row( $res );
  $list->set_row_link( 'contacts/view/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  $org = CORE_encode( $res['org_name'], F_HTM, F_SQL );

  if( !$csv )
  {
    if( $res['org_id'] )
      $org = '<a href="organizations/view/'.$res['org_id'].'">'.$org.'</a>';
    if( $res['org_count'] > 0 )
      $org .= ' and '.$res['org_count'].' other'.($res['org_count'] > 1 ? 's' : '');
  }
  $list->set_row_data( 'org_name', $org );

  if( !$csv )
    $list->set_row_data( 'email', preg_replace( $REGEX['email'], $REGEX['email_replace'][1], CORE_encode( $res['email'], F_HTM ) ) );

  if ($list->show_cols[$CONFERENCES_COL]) {
  
  	$conference_sql = "SELECT name FROM conferences cnf
           				LEFT JOIN interview_conferences AS icnf ON cnf.pk_id=icnf.fk_conference_id
  			              LEFT JOIN interviews AS i ON icnf.fk_interview_id=i.pk_id
  			             WHERE i.fk_contact_id=".$res['id'];
  	$conference_result = mysql_query( $conference_sql );
  	$conference_list = '';
  	while( $conference_res = mysql_fetch_assoc( $conference_result ) ){
  		if( !$csv ){
  			$conference_list = $conference_list . $conference_res['name'] . '<br>';
  		} else {
  			$conference_list = $conference_list . $conference_res['name'] . '\n';
  		}
  	}
  
  	$list->set_row_data( 'conferences', $conference_list);
  }
  
  $val = '<a href="contacts/view/'.$res['id'].'" title="view"><img width="16" height="16" src="img/icons/vcard.png"/></a>'.
         '<a href="contacts/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
         //'<a href="interviews/edit/c-'.$res['id'].'" title="add interview"><img width="16" height="16" src="img/icons/comments_add.png"/></a>'; 

  if( $USER->get('level') >= 5 )
    $val .= '<a href="javascript:delete_contact('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 
}


$my_contact->form->set_data( $search_data );


if( $search_string['all'] )
  $search_str = 'Showing all sources.';
elseif( $search_string )
  $search_str = 'Showing sources where: '.implode(' and ', $search_string ).'.';

$list->search_string = $search_str;
