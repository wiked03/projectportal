<?

function clean_notes_html($data){
	
	$data = str_replace('<h1>', '<p><b>', $data);
	$data = str_replace('</h1>', '</b></p>', $data);
	
	$data = str_replace('<h2>', '<p><b>', $data);
	$data = str_replace('</h2>', '</b></p>', $data);
	
	$data = str_replace('<h3>', '<p><b>', $data);
	$data = str_replace('</h3>', '</b></p>', $data);
	
	$data = str_replace('<h4>', '<p><b>', $data);
	$data = str_replace('</h4>', '</b></p>', $data);
	
	$data = str_replace('<h5>', '<p><b>', $data);
	$data = str_replace('</h5>', '</b></p>', $data);
	
	$data = str_replace('<p>', '', $data);
	$data = str_replace('</p>', '</br>', $data);
	
	$data = CORE_encode( $data, F_PHP );
	return $data;
}

// set 'any' as default
$search_data['type'] = 0;
$search_data['specialty']  = 0;
$search_data['degree'] = 0;
$search_data['is_source'] = 2;
$search_data['recontact'] = 2;
if( !$csv ){
  $search_data['col'] = '0.1.2.3.7.9.21';
}else{
  $search_data['col'] = '1.2.3.7.9.22';
}

$view = new View( );

global $LANG, $REGEX, $USER;

$var_string = CORE_decode( $var_string, F_URI );

// Override default list of columns
if( $USER->get('interview_defaults') ){
	$search_data['col'] = $USER->get('interview_defaults');
}

// ============================================================================
// URL line search strings

// --------------------
// (c) contact
if( preg_match( '/\/c-([\d]+)($|\/)/', $var_string, $matches ) )
{
  $where[] = "c.pk_id='".$matches[1]."'";
  $name = $view->get( 'contact', $matches[1], F_PHP );

  $search_data['first_name'] = $view->get( 'contact_fn', $matches[1], F_PHP );
  $search_data['last_name'] = $view->get( 'contact_ln', $matches[1], F_PHP );
  $search_string[] = '<span class="label">Contact</span> is "<span class="search_param">'.CORE_encode( $name, F_HTM ).'</span>"';
}
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
  
  // enable search for all users. AP
  $where[] = "c.first_name LIKE '%".$matches[1]."%'";

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
  // enable search for all users. AP
  $where[] = "c.last_name LIKE '%".$matches[1]."%'";

  $search_data['last_name'] = $matches[1];
  $search_string[] = '<span class="label">Last Name</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
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
  $q_str = $matches[1];

  $search_data['org_search'] = $q_str;

  if( preg_match_all( '/[\s]*(.*?)[\s]*(;|$)/', $q_str, $matches ) )
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
// (sd/ed) start date/end date
if( preg_match( '/\/(s|e)d-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  if( preg_match( '/\/sd-([\d]{8})($|\/)/', $var_string, $matches ) )
  {
    $my_date = CORE_date( $matches[1], G_DATE_FORMAT );

    $where[] = "i.int_date>='".CORE_date( $matches[1], F_DATE_SQL)."'";

    $search_data['start'] = $my_date;
    $date_after = '<span class="search_param">'.$my_date.'</span>';
  }
  if( preg_match( '/\/ed-([\d]{8})($|\/)/', $var_string, $matches ) )
  {
    $my_date = CORE_date( $matches[1], G_DATE_FORMAT );

    $where[] = "i.int_date<='".CORE_date( $matches[1], F_DATE_SQL)."'";

    $search_data['end'] = $my_date;
    $date_before = '<span class="search_param">'.$my_date.'</span>';
  }

  $search_data['select_date'] = '1';

  if( $date_before && $date_after )
    $search_string[] = '<span class="label">Interview Date</span> is between '.$date_after.' and '.$date_before;
  elseif( $date_before )
    $search_string[] = '<span class="label">Interview Date</span> is before '.$date_before;
  elseif( $date_after )
    $search_string[] = '<span class="label">Interview Date</span> is after '.$date_after;
}
// --------------------
// (dr) date range
if( preg_match( '/\/dr-(lw|lm|cm|ly|ytd)($|\/)/', $var_string, $matches ) )
{
  $begin_date = CORE_make_date( $matches[1], 0, F_DATE_SQL );
  $my_date = CORE_date( $begin_date, G_DATE_FORMAT );

  $where[] = "i.int_date>='".$begin_date."'";

  $search_data['start'] = $my_date;
  $date_after = '<span class="search_param">'.$my_date.'</span>';

  //----
  $end_date = CORE_make_date( $matches[1], 1, F_DATE_SQL );
  $my_date = CORE_date( $end_date, G_DATE_FORMAT );

  $where[] = "i.int_date<='".$end_date."'";

  $search_data['end'] = $my_date;
  $date_before = '<span class="search_param">'.$my_date.'</span>';

  $search_data['select_date'] = $matches[1];
  $search_string[] = '<span class="label">Interview Date</span> is between '.$date_after.' and '.$date_before;
}
// --------------------
// (prj) project
if( preg_match( '/\/prj-([\d]+)($|\/)/', $var_string, $matches ) )
{
  $where[] = "p.pk_id='".$matches[1]."'";
  $prj_name = $view->get( 'project', $matches[1], F_PHP );

  $search_data['project_list'] = $matches[1];
  $search_string[] = '<span class="label">Project</span> is "<span class="search_param">'.CORE_encode( $prj_name, F_HTM ).'</span>"';
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
    $where[] = "p.pk_id IN (".implode( ',', $q_str ).") ";
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
		$where[] = "cnf.pk_id IN (".implode( ',', $q_str ).") ";
		$search_data['conference_list'] = implode( '.', $q_str );
		$search_string[] = '<span class="label">Conference</span> is '.implode( ' or ', $s_str );
	}

}
// --------------------
// (anl) anylists
if( preg_match( '/\/anl-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $anl_name = $view->get( 'user', $value, F_PHP );
    if( $anl_name )
    {
      $s_str[] = '"<span class="search_param">'.$anl_name.'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "ui.pk_id IN (".implode( ',', $q_str ).") ";
    $search_data['analyst_list'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Primary Research Specialist</span> is '.implode( ' or ', $s_str );
  }

}
// --------------------
// (apr) approaches
if( preg_match( '/\/apr-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['approach'][$value] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['approach'][$value].'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "i.approach IN (".implode( ',', $q_str ).") ";
    $search_data['approaches'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Approach</span> is '.implode( ' or ', $s_str );
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
// (typ) type
if( preg_match( '/\/typ-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['source_types'][$value] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['source_types'][$value].'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "c.type IN (".implode( ',', $q_str ).") ";
    $search_data['type1'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Type</span> is '.implode( ' or ', $s_str );
  }
}
// --------------------
// (iid) interview id
if( preg_match( '/\/iid-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $s_str[] = '"<span class="search_param">'.$value.'</span>"';
    $q_str[] = $value;
  }

  if( $q_str )
  {
    $where[] = "i.pk_id IN (".implode( ',', $q_str ).") ";
    $search_data['pk_id'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">ID</span> is '.implode( ' or ', $s_str );
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
          $i_like[] = "( i.int_background LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%' ".
                       " OR i.int_notes LIKE '%".CORE_encode( $matches[1][$i], F_SQL2 )."%' )";
          $i_str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
        }
      }
    }

    $o_where[] = ' ( '.implode( ' AND ', $i_like ). '  )';
    $o_str[]  = implode( ' and ', $i_str );
  }

  $where[] = ' ( '.implode( ' OR ', $o_where ). '  )';
  $str[]  = implode( ' or ', $o_str );


  $search_string[] = '<span class="label">Relevant Interview Background</span> or <span class="label">PRS Notes</span> contains '.implode( ' and ', $str ); 
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
// (t) title
if( preg_match( '/\/t-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "c.title LIKE '%".$matches[1]."%'";

  $search_data['title'] = $matches[1];
  $search_string[] = '<span class="label">Title</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (txt) interview text
if( preg_match( '/\/txt-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['confidential'] = $matches[1];

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
          /*
          $i_like[] = "( i.int_background LIKE '%".$matches[1][$i]."%' OR i.confidential LIKE '%".$matches[1][$i]."%'
                  OR i.source_comments LIKE '%".$matches[1][$i]."%'
                  OR i.analyst_comments LIKE '%".$matches[1][$i]."%' OR i.notes LIKE '%".$matches[1][$i]."%')";
          */
          $i_like[] = "( i.int_background LIKE '%".$matches[1][$i]."%' OR i.confidential LIKE '%".$matches[1][$i]."%'
                  OR i.int_notes LIKE '%".$matches[1][$i]."%')";

          $i_str[]  = '"<span class="search_param">'.CORE_encode( $matches[1][$i], F_HTM ).'</span>"';
        }
      }
    }

    $o_where[] = ' ( '.implode( ' AND ', $i_like ). '  )';
    $o_str[]  = implode( ' and ', $i_str );
  }

  $where[] = ' ( '.implode( ' OR ', $o_where ). '  )';
  $str[]  = implode( ' or ', $o_str );


  $search_string[] = '<span class="label">Interview Text</span> contains '.implode( ' and ', $str ); 
}
// --------------------
// (id) interview_id
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
        $like_str = "c.pk_id = '".CORE_encode( $matches[2][$i], F_SQL2 )."'";
        if( $matches[3][$i] )
          $like_str .= " AND i.int_number='".CORE_encode( $matches[3][$i], F_SQL2 )."'";
        $like[] = $like_str;
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
    $search_data['specialty1'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Therapeutic Area</span> is '.implode( ' or ', $s_str );
  }

}
// --------------------
// (ac) activity
if( preg_match( '/\/ac-(0)($|\/)/', $var_string, $matches ) )
{
  $search_string[] = 'not including <span class="label">Activities</span>';
  $where[] = "NOT is_activity";
  $search_data['is_activity1'] = 0; 
}
else
{
  $search_data['is_activity1'] = 1;
}
// --------------------
// (lay) layout fields
if( preg_match( '/\/lay-([^\/]+)/', $var_string, $matches ) )
{
	$save_layout = $matches[1];
	if($save_layout=='1'){
		$layout_to_save = $search_data['col'];

		$sql = "UPDATE users SET interview_defaults = '".
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



if( isset( $where ) )
{
  $where = "WHERE ".implode( " AND ", $where );
  $show_list = true;
}

$from = "FROM interviews AS i
           LEFT JOIN interview_projects AS ip ON i.pk_id=ip.fk_interview_id
           LEFT JOIN projects AS p ON p.pk_id=ip.fk_project_id
           LEFT JOIN interview_conferences AS icnf ON i.pk_id=icnf.fk_interview_id
           LEFT JOIN conferences AS cnf ON cnf.pk_id=icnf.fk_conference_id
		   LEFT JOIN user_info AS ui ON ui.pk_id=i.fk_user_id
           LEFT JOIN contacts AS c ON c.pk_id=i.fk_contact_id
           LEFT JOIN contact_orgs AS co ON co.fk_contact_id=i.fk_contact_id
           LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id
           LEFT JOIN user_projects
           		AS up ON up.fk_project_id=ip.fk_project_id AND up.fk_user_id=".$USER->get('id');

// ---------------------------------------------------
// get count from DB
$sql = "SELECT COUNT( DISTINCT i.pk_id ) AS total
        ".$from."
        ".$where;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

// ---------------------------------------------------
$list->set_total( $total );
$list->no_footer = true;

if( !$csv )
  $list->add_column( 'is_activity',     '&nbsp;', 25, 'center' ); // 0
else
  $list->add_column( 'blank',     '', 25, 'center' ); // 0

$list->add_column( 's_id',    'ID', 65, 'right' ); // 1
$list->add_column( 'int_date',     'Date', 60, 'right' ); // 2
$list->add_column( 'contact_name', 'Source Name', 150 ); // 3
$list->add_column( 'first_name',   'First Name', 70 ); // 4
$list->add_column( 'last_name',    'Last Name', 90 ); // 5
$list->add_column( 'org_name',     'Organization', 200 ); // 6

$list->add_column( 'proj_name',    'Project', 205 ); // 7
$list->add_column( 'conferences',     'Conferences', 205 ); // 8
$CONFERENCES_COL = 8;
$list->add_column( 'analyst',      'Primary Research Specialist', 125 ); // 9

$list->add_column( 'credibility',  'Credibility', 70, 'center' ); // 10

$list->add_column( 'title',        'Title', 145 ); // 11

$list->add_column( 'city',     'City', 100 ); // 12
$list->add_column( 'state',     'State', 40, 'center' ); // 13
$list->add_column( 'country',     'Country', 100 ); // 14
$list->add_column( 'spec',    'Therapeutic Area', 130 ); // 15
$list->add_column( 'degree',       'Degree', 55 ); // 16
$list->add_column( 'type',         'Type', 40, 'center' ); // 17

$list->add_column( 'confidential', 'Interview and Key Takeaways', 250 ); // 18
$list->add_column( 'int_background', 'Relevant Interview Background', 250 ); // 19

// Now these fields are combined into "Interview and Key Takeaways" (formely confidential)
//$list->add_column( 'source_comments', 'Source Comments', 250 ); // 19
//$list->add_column( 'analyst_comments', 'Analyst Comments', 250 ); // 20
$list->add_column( 'int_notes', 'PRS Notes', 250 ); // 20

if( !$csv && $USER->get('level') >= 2 )
  $list->add_column( 'actions',      'Actions',     75, 'center', true ); // 21
elseif( !$csv )
  $list->add_column( 'actions',      'Actions',     50, 'center', true ); // 21
else
  $list->add_column( 'blank2',     '', 25, 'center' ); // 22

//$list->required_columns( '0' );
$list->default_columns( $search_data['col'] );

// ---------------------------------------------------
// run query
$sql = "SELECT i.pk_id AS id, i.*,
			CONCAT_WS( ', ',c.last_name,c.first_name) AS contact_name,
			c.*, c.fk_created_by_user AS creator, 
            o.name AS org_name,
			o.pk_id AS org_id,
			(COUNT( DISTINCT co.fk_organization_id ) - 1) AS org_count,
			LPAD(SUBSTRING_INDEX(c.specialty, '.', 1),4,'0') AS spec,
            CONCAT_WS( ' ',ui.first_name,ui.last_name) AS analyst,
			p.name AS proj_name,
			p.pk_id AS proj_id,
			(COUNT( DISTINCT ip.fk_project_id ) - 1) AS proj_count, 
            CONCAT_WS( '-', LPAD(c.pk_id,10,'0'), 
			LPAD(i.int_number,10,'0') ) AS s_id,
			co.city AS city, co.state AS state,
			co.country AS country,
			c.is_source AS is_source,
            COUNT( DISTINCT up.fk_project_id ) AS up_count
        ".$from."
        ".$where."
        GROUP BY id
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();

//echo $sql;
//echo '<br>';

$result = mysql_query( $sql );

// ---------------------------------------------------

while( $res = mysql_fetch_assoc( $result ) )
{
  if( $res['is_source'] == 3 )
  {
    $res['contact_name'] = $LANG['anonymous'];
  }

  // AP: All the contacts names are visible now, the only exception
  //     is when the contact is a Personal Source, which is enforced above.
  // if user is analyst, lock contact name
  //if( $USER->get('level')<=2 && ($res['creator'] != $USER->get('id') && !$res['up_count']) )
  //  $res['contact_name'] = $LANG['source_hidden'];

  $res['s_id'] = $LANG['source_types_short'][$res['type']].'-'.sprintf( '%03d', $res['fk_contact_id'] ).'-'.$res['int_number'];

  $res['type'] = $LANG['source_types_short'][ $res['type'] ];
  $res['country'] = $LANG['countries'][ $res['country'] ];

  $degree_str = array();
  $degrees = explode( '.', $res['degree'] );
  foreach( $degrees as $degree )
    $degree_str[] = $LANG['degrees'][$degree];
  $res['degree'] = implode( ', ', $degree_str );

  $val_str = array();
  $values = explode( '.', $res['spec'] );
  foreach( $values as $value )
    $val_str[] = $LANG['specialties'][(int)$value];
  $res['spec'] = implode( ', ', $val_str );

  $res['approach'] = $LANG['approach'][ $res['approach'] ];
  $res['credibility'] = $LANG['credibility_short'][ $res['credibility'] ];

  $res['int_date'] = CORE_date( $res['int_date'], F_DATE_HTM );
  
  /*
  $res['int_background'] = str_replace('<br>', '<br>\n', $res['int_background']);
  $res['int_background'] = str_replace('</br>', '</br>\n', $res['int_background']);
  $res['int_background'] = str_replace('</p>', '</p>\n', $res['int_background']);
  $res['int_background'] = strip_tags($res['int_background']);
  */
  
  $list->add_row( $res );
  $list->set_row_link( 'interviews/view/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  // html fields
  $list->set_row_data( 'int_background', clean_notes_html($res['int_background']) );
  $list->set_row_data( 'int_notes', clean_notes_html($res['int_notes']) );
  $list->set_row_data( 'confidential', clean_notes_html($res['confidential']) );
  
  $org = CORE_encode( $res['org_name'], F_HTM, F_SQL );
  if( !$csv )
  {
    if( $res['org_id'] )
      $org = '<a href="organizations/view/'.$res['org_id'].'">'.$org.'</a>';
    if( $res['org_count'] > 0 )
      $org .= ' and '.$res['org_count'].' other'.($res['org_count'] > 1 ? 's' : '');
  }
  $list->set_row_data( 'org_name', $org );


  $proj = CORE_encode( $res['proj_name'], F_HTM, F_SQL );
  if( !$csv )
  {
    if( $res['proj_id'] )
      $proj = '<a href="projects/view/'.$res['proj_id'].'">'.$proj.'</a>';
    if( $res['proj_count'] > 0 )
      $proj .= ' and '.$res['proj_count'].' other'.($res['proj_count'] > 1 ? 's' : '');
  }
  $list->set_row_data( 'proj_name', $proj );
  
  if ($list->show_cols[$CONFERENCES_COL]) {
  
  	$conference_sql = "SELECT name FROM conferences cnf
           				LEFT JOIN interview_conferences AS icnf ON cnf.pk_id=icnf.fk_conference_id
  			             WHERE icnf.fk_interview_id=".$res['id'];
  	$conference_result = mysql_query( $conference_sql );
  	$conference_list = '';
  	while( $conference_res = mysql_fetch_assoc( $conference_result ) ){
  		$conference_list = $conference_list . $conference_res['name'] . '<br>';
  	}
  
  	$list->set_row_data( 'conferences', $conference_list);
  }

  if( !$csv )
  {
    $contact = '<a href="contacts/view/'.$res['fk_contact_id'].'">'.CORE_encode( $res['contact_name'], F_HTM, F_SQL ).'</a>';
    $list->set_row_data( 'contact_name', $contact );
  }

  if( !$csv )
  {
    $val = '<img width="16" height="16" src="img/icons/comments.png"/>';
    if( $res['is_activity'] )
      $val = '<img width="16" height="16" src="img/icons/phone.png"/>';

    $list->set_row_data( 'is_activity', $val );
  }

  $val = '<a href="interviews/view/'.$res['id'].'" title="view"><img width="16" height="16" src="img/icons/comments.png"/></a>'.
         '<a href="interviews/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';

  if( $USER->get('level') >= 3 )
    $val .= '<a href="javascript:delete_int('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 

}


$my_obj->form->set_data( $search_data );


if( $search_string['all'] )
  $search_str = 'Showing all interviews.';
elseif( $search_string )
  $search_str = 'Showing interviews where: '.implode(' and ', $search_string ).'.';

$list->search_string = $search_str;
