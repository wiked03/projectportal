<?

// set 'any' as default
$search_data['type'] = 0;
$search_data['specialty']  = 0;
$search_data['industry']  = 0;
$search_data['degree'] = 0;
$search_data['is_source'] = 2;
$search_data['recontact'] = 2;
$search_data['col'] = '0.1.4.25.26.27';

global $LANG, $REGEX, $USER;

$edit_lock = 1;
if( $USER->get('level') <= 2  )
  $edit_lock = 0;

$view = new View( );

$var_string = CORE_decode( $var_string, F_URI );

// Override default list of columns
if( $USER->get('project_defaults') ){
	$search_data['col'] = $USER->get('project_defaults');
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
// (na) name
if( preg_match( '/\/na-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "p.name LIKE '%".$matches[1]."%'";

  $search_data['name'] = $matches[1];
  $search_string[] = '<span class="label">Name</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (de) description
if( preg_match( '/\/de-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "p.description LIKE '%".$matches[1]."%'";

  $search_data['description'] = $matches[1];
  $search_string[] = '<span class="label">Description</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (not) notes
if( preg_match( '/\/not-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "p.notes LIKE '%".$matches[1]."%'";

  $search_data['notes'] = $matches[1];
  $search_string[] = '<span class="label">Notes</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}

// --------------------
// (bdpoc) bd_poc
if( preg_match( '/\/bdpoc-([^\/]+)/', $var_string, $matches ) )
{
	$where[] = "p.bd_poc LIKE '%".$matches[1]."%'";

	$search_data['bd_poc'] = $matches[1];
	$search_string[] = '<span class="label">Business Development Point of Contact</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
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
    $where[] = "p.specialty REGEXP '(\\^|\\.)(".implode( '|', $q_str ).")($|\\.)'";
    $search_data['specialty1'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Therapeutic Area</span> is '.implode( ' or ', $s_str );
  }

}
// --------------------
// (in) industry
if( preg_match( '/\/in-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    if( isset( $LANG['industries'][$value] ) )
    {
      $s_str[] = '"<span class="search_param">'.$LANG['industries'][$value].'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "p.industry REGEXP '(\\^|\\.)(".implode( '|', $q_str ).")($|\\.)'";
    $search_data['industry'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Industry</span> is '.implode( ' or ', $s_str );
  }

}
// --------------------
// (poc) point of contact
if( preg_match( '/\/poc-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "p.poc LIKE '%".$matches[1]."%'";

  $search_data['poc'] = $matches[1];
  $search_string[] = '<span class="label">Point of Contact</span> like "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
/*
if( preg_match( '/\/poc-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $prj_name = $view->get( 'contact', $value, F_PHP );
    if( $prj_name )
    {
      $s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "fk_poc_id IN (".implode( ',', $q_str ).") ";
    $search_data['project_contractor_list'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Point of Contact</span> is '.implode( ' or ', $s_str );
  }
}
* */
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
// (sd) start date
if( preg_match( '/\/sd-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  $my_date = CORE_date( $matches[1], G_DATE_FORMAT );
  $where[] = "p.start >= '".CORE_date( $matches[1], F_DATE_SQL)."'";

  $search_data['start'] = $my_date;
  $search_string[] = '<span class="label">Start Date</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (ed) end date
if( preg_match( '/\/ed-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  $my_date = CORE_date( $matches[1], G_DATE_FORMAT );
  $where[] = "p.end <= '".CORE_date( $matches[1], F_DATE_SQL)."'";

  $search_data['end'] = $matches[1];
  $search_string[] = '<span class="label">End Date</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (dir) director
if( preg_match( '/\/dir-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
	// make sure valid
	$vals = explode( '.', $matches[1] );
	$q_str = NULL;
	$s_str = '';

	foreach( $vals as $value )
	{
		$prj_name = $view->get( 'user', $value, F_PHP );
		if( $prj_name )
		{
			$s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
			$q_str[] = $value;
		}
	}

	if( $q_str )
	{
		$where[] = "p.fk_dir_id IN (".implode( ',', $q_str ).") ";
		$search_data['project_directors_list'] = implode( '.', $q_str );
		$search_string[] = '<span class="label">Director</span> is '.implode( ' or ', $s_str );
	}
}
// --------------------
// (pid) manager
if( preg_match( '/\/pid-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $prj_name = $view->get( 'user', $value, F_PHP );
    if( $prj_name )
    {
      $s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "p.fk_pm_id IN (".implode( ',', $q_str ).") ";
    $search_data['project_managers_list'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Lead Analyst</span> is '.implode( ' or ', $s_str );
  }
}
// --------------------
// (an) analyst
if( preg_match( '/\/an-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $prj_name = $view->get( 'user', $value, F_PHP );
    if( $prj_name )
    {
      $s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "up.fk_user_id IN (".implode( ',', $q_str ).") ";
    $search_data['project_analysts_list'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Analyst</span> is '.implode( ' or ', $s_str );
  }
}

// --------------------
// (coll) collector
if( preg_match( '/\/coll-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
	// make sure valid
	$vals = explode( '.', $matches[1] );
	$q_str = NULL;
	$s_str = '';

	foreach( $vals as $value )
	{
		$prj_name = $view->get( 'user', $value, F_PHP );
		if( $prj_name )
		{
			$s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
			$q_str[] = $value;
		}
	}

	if( $q_str )
	{
		$where[] = "collp.fk_user_id IN (".implode( ',', $q_str ).") ";
		$search_data['project_collectors_list'] = implode( '.', $q_str );
		$search_string[] = '<span class="label">Primary Research Specialist</span> is '.implode( ' or ', $s_str );
	}
}

// --------------------
// (con) contractor
if( preg_match( '/\/con-([\d\.]+)($|\/)/', $var_string, $matches ) )
{
  // make sure valid
  $vals = explode( '.', $matches[1] );
  $q_str = NULL;
  $s_str = '';

  foreach( $vals as $value )
  {
    $prj_name = $view->get( 'contact', $value, F_PHP );
    if( $prj_name )
    {
      $s_str[] = '"<span class="search_param">'.$prj_name.'</span>"';
      $q_str[] = $value;
    }
  }

  if( $q_str )
  {
    $where[] = "cp.fk_contact_id IN (".implode( ',', $q_str ).") ";
    $search_data['project_contractor_list'] = implode( '.', $q_str );
    $search_string[] = '<span class="label">Contractor</span> is '.implode( ' or ', $s_str );
  }
}

// --------------------
// (ia) status
if( preg_match( '/\/ia-([^\/]+)/', $var_string, $matches ) )
{
  

  $search_data['is_active'] = $matches[1];
  if ($matches[1] == '1'){
  	$search_string[] = '<span class="label">Status</span> is "Open"';
  	$where[] = "p.is_active = '".$matches[1]."'";
  } else {
  	if ($matches[1] == '0'){
	  	$search_string[] = '<span class="label">Status</span> is "Closed"';
	  	$where[] = "p.is_active = '".$matches[1]."'";
  	} else {
  		$search_string[] = '<span class="label">Status</span> is "All"';
  	}
  }
}

// --------------------
// (ils) is life science?
if( preg_match( '/\/ils-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "p.is_life_science = '".$matches[1]."'";

  $search_data['is_life_science'] = $matches[1];
  if ($matches[1] == '1'){
  	$search_string[] = '<span class="label">Type</span> is Life Science"';
  } else {
  	$search_string[] = '<span class="label">Type</span> is Non-Life Science"';
  }
}

// --------------------
// (pfx) prefix
if( preg_match( '/\/pfx-([^\/]+)/', $var_string, $matches ) )
{
	$search_data['prefix'] = $matches[1];
	if($matches[1]=='0'){
		$search_string[] = '<span class="label">Prefix</span> is "Any"';
	} else {
		$where[] = "p.prefix = '".$matches[1]."'";
		$search_string[] = '<span class="label">Prefix</span> is "'.$LANG['prefix'][$matches[1]].'"';
	}
}


// --------------------
// (id) project_id
if( preg_match( '/\/id-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['project_id'] = $matches[1];

  $str = array();
  $like = array();
  if( preg_match_all( '/[\s]*((?:[a-zA-Z]{3}\-?)?([\d]{3,})(?:\-([\d]+))?)[\s]*(;|$)/', $matches[1], $matches ) )
  {
    for( $i = 0; isset($matches[2][$i]); $i++ )
    {
      if( $matches[2][$i] )
      {
        $like[] = "p.pk_id = '".CORE_encode( $matches[2][$i], F_SQL2 )."'";
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
    $search_data['project_id'] = '';
}
// --------------------
// (lay) layout fields
if( preg_match( '/\/lay-([^\/]+)/', $var_string, $matches ) )
{
	$save_layout = $matches[1];
	if($save_layout=='1'){
		$layout_to_save = $search_data['col'];
		
		$sql = "UPDATE users SET project_defaults = '".
            $layout_to_save."' WHERE pk_id = " .
            $USER->get('id');
		$result = mysql_query( $sql );		
	}
}

// --------------------
// (cnf) conferences
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
		$where[] = "cnf.fk_conference_id IN (".implode( ',', $q_str ).") ";
		$search_data['conference_list'] = implode( '.', $q_str );
		$search_string[] = '<span class="label">Conference</span> is '.implode( ' or ', $s_str );
	}

}

// --------------------
// (v1) start value
if( preg_match( '/\/v1-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['v1'] = $matches[1];
  $where[] = "p.value >= '".$matches[1]."'";
  $search_string[] = '<span class="label">Value</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (v2) end
if( preg_match( '/\/v2-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['v2'] = $matches[1];
  $where[] = "p.value <= '".$matches[1]."'";
  $search_string[] = '<span class="label">Value</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}


// --------------------
// (e1) start value
if( preg_match( '/\/e1-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['e1'] = $matches[1];
  $where[] = "coalesce((SELECT sum(int_amount) FROM expenses WHERE fk_project_id = p.pk_id  ),0) >= '".$matches[1]."'";
  $search_string[] = '<span class="label">Expenses</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (e2) end
if( preg_match( '/\/e2-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['e2'] = $matches[1];
  $where[] = "coalesce((SELECT sum(int_amount) FROM expenses WHERE fk_project_id = p.pk_id  ),0) <= '".$matches[1]."'";
  $search_string[] = '<span class="label">Expenses</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}


// --------------------
// (b1) start value
if( preg_match( '/\/b1-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['b1'] = $matches[1];
  $where[] = "(p.value - coalesce((SELECT sum(int_amount) FROM expenses WHERE fk_project_id = p.pk_id  ),0)) >= '".$matches[1]."'";
  $search_string[] = '<span class="label">Balance</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (b2) end
if( preg_match( '/\/b2-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['b2'] = $matches[1];
  $where[] = "(p.value - coalesce((SELECT sum(int_amount) FROM expenses WHERE fk_project_id = p.pk_id  ),0)) <= '".$matches[1]."'";
  $search_string[] = '<span class="label">Balance</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}


// --------------------
// (h1) start value
if( preg_match( '/\/h1-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['h1'] = $matches[1];
  $where[] = "p.hourly_rate >= '".$matches[1]."'";
  $search_string[] = '<span class="label">Hourly Rate</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (h2) end
if( preg_match( '/\/h2-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['h2'] = $matches[1];
  $where[] = "p.hourly_rate <= '".$matches[1]."'";
  $search_string[] = '<span class="label">Hourly Rate</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}



// --------------------
// (eh1) start value
if( preg_match( '/\/eh1-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['eh1'] = $matches[1];
  $where[] = "coalesce((value / hourly_rate), 0) >= '".$matches[1]."'";
  $search_string[] = '<span class="label">Estimated Hours</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (eh2) end
if( preg_match( '/\/eh2-([^\/]+)/', $var_string, $matches ) )
{
  $search_data['eh2'] = $matches[1];
  $where[] = "coalesce((value / hourly_rate), 0) <= '".$matches[1]."'";
  $search_string[] = '<span class="label">Estimated Hours</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}

// --------------------
// (all) all
if( preg_match( '/\/all($|\/)/', $var_string, $matches ) )
{
  $where[] = 1;
  $search_string['all'] = true;
}


// ============================================================================
//
//    INTERVIEW PARAMETERS
//
// ============================================================================

// --------------------
// (sd) start date
if( preg_match( '/\/sdiec-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  $my_date = CORE_date( $matches[1], G_DATE_FORMAT );
  $where_interview[] = "int_date >= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_expense[] = "int_date >= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_contribution[] = "int_start_date >= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_update[] = "int_start_date >= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_deliverable[] = "int_start_date >= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  
  $search_data['start'] = $my_date;
  $search_string[] = '<span class="label">Int/Exp/Cont/Status/Deliv Date</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (ed) end date
if( preg_match( '/\/ediec-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  $my_date = CORE_date( $matches[1], G_DATE_FORMAT );
  $where_interview[] = "int_date <= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_expense[] = "int_date <= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_contribution[] = "int_end_date <= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_update[] = "int_start_date <= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  $where_deliverable[] = "int_start_date <= '".CORE_date( $matches[1], F_DATE_SQL)."'";
  
  $search_data['end'] = $matches[1];
  $search_string[] = '<span class="label">Int/Exp/Cont/Status/Deliv Date</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
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

if( isset( $where_interview ) )
{
  $where_interview = "AND ".implode( " AND ", $where_interview );
}

if( isset( $where_expense ) )
{
  $where_expense = "AND ".implode( " AND ", $where_expense );
}

if( isset( $where_contribution ) )
{
  $where_contribution = "AND ".implode( " AND ", $where_contribution );
}

if( isset( $where_update ) )
{
  $where_update = "AND ".implode( " AND ", $where_update );
}

if( isset( $where_deliverable ) )
{
	$where_deliverable = "AND ".implode( " AND ", $where_deliverable );
}

$from = "FROM projects AS p
           LEFT JOIN conference_projects AS cnf ON cnf.fk_project_id=p.pk_id
           LEFT JOIN user_projects AS up ON up.fk_project_id=p.pk_id
		   LEFT JOIN collector_projects AS collp ON collp.fk_project_id=p.pk_id
           LEFT JOIN contractor_projects AS cp ON cp.fk_project_id=p.pk_id
           LEFT JOIN organizations AS o ON o.pk_id=p.fk_client_id
           LEFT JOIN organizations AS t ON t.pk_id=p.fk_target_id
           LEFT JOIN contacts AS con ON con.pk_id=p.fk_poc_id
           LEFT JOIN user_info AS um ON um.pk_id=p.fk_pm_id
		   LEFT JOIN user_info AS um2 ON um2.pk_id=p.fk_dir_id";

// ---------------------------------------------------
// get count from DB
$sql = "SELECT COUNT( DISTINCT p.pk_id ) AS total
        ".$from."
        ".$where;

//echo '<br>';
//echo $sql;
//echo '<br>';

$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

// ---------------------------------------------------
$list->set_total( $total );
$list->no_footer = true;

$list->add_column( 's_id',    'ID', 65, 'right' ); // 0
$list->add_column( 'name',   'Name', 260 ); // 1
//$list->add_column( 'prefix', 'Prefix', 50 ); // 2
$list->add_column( 'description', 'Description', 100, 'right', false ); // 2
$list->add_column( 'org_name',    'Organization', 200 ); // 3
$list->add_column( 'poc', 'Point of Contact', 100, 'right', false ); // 4
$list->add_column( 'director',     'Director', 100 ); // 5
$list->add_column( 'manager',     'Lead Analyst', 100 ); // 6
$list->add_column( 'analysts',    'Analysts', 260 ); // 7
$list->add_column( 'collectors',  'Prim. Research Specialist', 260 ); // 8
$list->add_column( 'contractors', 'Contractors', 260 ); // 9
$list->add_column( 'specialty', 'Therapeutic Area', 160 ); // 10

$list->add_column( 'value', 'Research Budget', 60, 'right', false ); // 11
$list->add_column( 'conferences_value', 'Conference Budget', 60, 'right', false ); // 12
$list->add_column( 'expenses', 'Expenses Total', 60, 'right', false ); // 13

$list->add_column( 'pct_spent', '% Spent', 60, 'right', false ); // 14

$list->add_column( 'balance', 'Balance', 60, 'right', false ); // 15
$list->add_column( 'hourly_rate', 'Hourly Rate', 60, 'right', false ); // 16
$list->add_column( 'estimated_hours', 'Estimated Hours', 60, 'right', false ); // 17

$list->add_column( 'interview_list', 'Interviews', 360 ); // 18
$INTERVIEWS_COL = 18;

$list->add_column( 'expenses_list', 'Expenses Breakdown', 360 ); // 19
$EXPENSES_COL = 19;

//$list->add_column( 'contributions_list', 'Contributions', 360 ); // 19
//$CONTRIBUTIONS_COL = 19;

//$list->add_column( 'updates_list', 'Status', 360 ); // 20
//$UPDATES_COL = 20;

$list->add_column( 'deliverables_list', 'Deliverables', 360 ); // 20
$DELIVERABLES_COL = 20;

$list->add_column( 'conferences',     'Conferences', 205 ); // 21
$CONFERENCES_COL = 21;

$list->add_column( 'is_life_science', 'Type', 160 ); // 22
$list->add_column( 'industry', 'Industry', 160 ); // 23
$list->add_column( 'bd_poc', 'Business Development Point of Contact', 160 ); // 24

$list->add_column( 'start',     'Start Date', 100 ); // 25
$list->add_column( 'end',     'End Date', 100 ); // 26

if( !$csv && $USER->get('level') >= 5 )
  $list->add_column( 'actions',      'Actions',     100, 'center', true ); // 27
elseif( !$csv )
  $list->add_column( 'actions',      'Actions',     75, 'center', true ); // 27

//$list->required_columns( '0.1' );
$list->default_columns( $search_data['col'] );

// ---------------------------------------------------
// run query
$sql = "SELECT p.pk_id AS id, p.*, p.pk_id AS s_id,
           o.name AS org_name, o.pk_id AS org_id,
           CONCAT_WS(' ', con.first_name, con.last_name) AS contact,
           CONCAT_WS(' ', um2.first_name, um2.last_name) AS director,
           CONCAT_WS(' ', um.first_name, um.last_name) AS manager,
           (SELECT group_concat(CONCAT_WS(' ', TRIM(ui.first_name), TRIM(ui.last_name)) SEPARATOR ', ') FROM projects p2 
              LEFT JOIN user_projects AS up ON up.fk_project_id=p2.pk_id
              LEFT JOIN user_info AS ui ON up.fk_user_id = ui.pk_id
             where p2.pk_id = p.pk_id
              group by p2.pk_id) AS analysts,
           (SELECT group_concat(CONCAT_WS(' ', TRIM(ui.first_name), TRIM(ui.last_name)) SEPARATOR ', ') FROM projects p2 
              LEFT JOIN collector_projects AS up ON up.fk_project_id=p2.pk_id
              LEFT JOIN user_info AS ui ON up.fk_user_id = ui.pk_id
             where p2.pk_id = p.pk_id
              group by p2.pk_id) AS collectors,
           (SELECT group_concat(CONCAT_WS(' ', TRIM(con.first_name), TRIM(con.last_name)) SEPARATOR ', ') FROM projects p2
              LEFT JOIN contractor_projects AS cp ON cp.fk_project_id=p2.pk_id
              LEFT JOIN contacts AS con ON cp.fk_contact_id = con.pk_id
             where p2.pk_id = p.pk_id
              group by p2.pk_id) AS contractors,
           coalesce((SELECT COALESCE(sum(int_amount), 0) FROM expenses WHERE fk_project_id = p.pk_id ".$where_expense." ),0) as expenses,
           coalesce((SELECT SUM(REPLACE(rate, '$', '')) AS sum_expenses
                      FROM interviews i
                      INNER JOIN interview_projects ip ON i.pk_id = ip.fk_interview_id
                      WHERE ip.fk_project_id = p.pk_id AND paid = 1 ".$where_expense."),0) as honoraria,
           COALESCE((SELECT sum(int_amount) FROM conf_projs WHERE fk_project_id = p.pk_id  ),0) as conferences_value,      		
            value - COALESCE((SELECT sum(int_amount) FROM expenses WHERE fk_project_id = p.pk_id  ),0) 
                  + COALESCE((SELECT sum(int_amount) FROM conf_projs WHERE fk_project_id = p.pk_id  ),0) as balance,
              format(COALESCE((value / hourly_rate), 0),0) as estimated_hours,
             '' as interview_list,
             '' as expenses_list,
             '' as contributions_list,
             '' as updates_list
        ".$from."
        ".$where."
        GROUP BY id
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();

//echo $sql;
$result = mysql_query( $sql );

// ---------------------------------------------------
while( $res = mysql_fetch_assoc( $result ) )
{
 	if($res['prefix']>0){
 		$res['s_id'] = sprintf('%s-%06d', $LANG['prefix'][ $res['prefix'] ], $res['s_id'] );
 	} else {
 		$res['s_id'] = sprintf('   %06d', $res['s_id'] );
 	}
  

  $res['prefix'] = $LANG['prefix'][ $res['prefix'] ];
  $res['is_life_science'] = $LANG['life_science'][ $res['is_life_science'] ];
  $res['country'] = $LANG['countries'][ $res['country'] ];
  $res['country'] = $LANG['countries'][ $res['country'] ];

  $res['recontact'] = $LANG['yes'][$res['recontact']];

  $res['expenses'] = $res['expenses'] + $res['honoraria'];

  if($res['value']>0){
    $res['pct_spent'] =  round((($res['expenses']) / ($res['value']) * 100), 2);
  } else {
    $res['pct_spent'] = 0;
  }

  $res['value'] = format_currency($res['value']);
  $res['conferences_value'] = format_currency($res['conferences_value']);
  $res['expenses'] = format_currency($res['expenses']);
  $res['balance'] = format_currency($res['balance']);

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

  $val_str = array();
  $values = explode( '.', $res['industry'] );
  foreach( $values as $value )
    $val_str[] = $LANG['industries'][(int)$value];
  $res['industry'] = implode( ', ', $val_str );

  $res['is_source'] = $LANG['contact_types'][ $res['is_source'] ];
  $res['reliability'] = $LANG['reliability_short'][ $res['reliability'] ];
  $res['phone'] = CORE_phone( $res['phone'], F_HTM );

  $list->add_row( $res );
  $list->set_row_link( 'projects/view/'.$res['id'] );
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

  $val = '<a href="projects/view/'.$res['id'].'" title="view"><img width="16" height="16" src="img/icons/report.png"/></a>';
  if( $edit_lock )
    $val .= '<a href="projects/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
  if( $USER->get( 'level' ) >= 5 )
    $val .= '<a href="javascript:delete_proj('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';
  $list->set_row_data( 'actions', $val ); 

  if ($list->show_cols[$INTERVIEWS_COL]) {

    $interview_sql = "SELECT CONCAT_WS(' ',  DATE_FORMAT(int_date, '%m/%d/%Y'), 'interview of', 
                                  c.first_name, c.last_name,
                                  'by', ui.first_name, ui.last_name) as interview
                    FROM interviews AS i
                      LEFT JOIN user_info AS ui ON ui.pk_id=i.fk_user_id
                      LEFT JOIN interview_projects AS ip ON ip.fk_interview_id=i.pk_id
                      LEFT JOIN contacts AS c ON c.pk_id=i.fk_contact_id
                    WHERE ip.fk_project_id=".$res['id']."
                      AND NOT is_activity ".$where_interview."
                    ORDER BY i.int_date DESC";
    //echo $interview_sql;
    $interview_result = mysql_query( $interview_sql );
    $interview_list = '';
    while( $interview_res = mysql_fetch_assoc( $interview_result ) ){
        $interview_list = $interview_list . $interview_res['interview'] . '<br>';
    }

    $list->set_row_data( 'interview_list', $interview_list);
  }

  if ($list->show_cols[$EXPENSES_COL]) {

    $expense_sql = " SELECT * FROM (SELECT CONCAT_WS(' ', DATE_FORMAT(int_date, '%m/%d/%Y'), '-',
                                          CONCAT('$', FORMAT(int_amount, 2)), 
                                          ' expense of', 
                                          CONCAT_WS(' ', co.first_name, co.last_name)) as expense,
    		                              int_amount
                    FROM expenses AS exp
                      LEFT JOIN contacts AS co ON co.pk_id=exp.fk_contractor_id
                      LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
                    WHERE exp.fk_project_id=".$res['id']." ".$where_expense."
                    UNION
                     SELECT CONCAT_WS(' ', DATE_FORMAT(int_date, '%m/%d/%Y'), '-',
                                          CONCAT('$', FORMAT(SUM(REPLACE(rate, '$', '')), 2)), 
                                          ' expense of', COUNT(1), ' Honoraria'
                    		               ) as expense,
                    		             SUM(REPLACE(rate, '$', '')) as int_amount
                    FROM interviews i
                    INNER JOIN interview_projects ip ON i.pk_id = ip.fk_interview_id
                     WHERE ip.fk_project_id =".$res['id']." ".$where_expense."
                     AND paid = 1) as t
                    ORDER BY int_amount";
    //echo $expense_sql;
    $expense_result = mysql_query( $expense_sql );
    $expense_list = '';
    while( $expense_res = mysql_fetch_assoc( $expense_result ) ){
        $expense_list = $expense_list . $expense_res['expense'] . '<br>';
    }

    $list->set_row_data( 'expenses_list', $expense_list);
  }

  if ($list->show_cols[$CONTRIBUTIONS_COL]) {

    $contribution_sql = "SELECT CONCAT_WS(' ', 'Contribution of', CONCAT_WS(' ', co.first_name, co.last_name), CONCAT(FORMAT(effort, 0), '%'), 
                       '(', DATE_FORMAT(int_start_date, '%m/%d/%Y'), '-', DATE_FORMAT(int_end_date, '%m/%d/%Y'), ')' )  
                                        as contribution
                          FROM resources AS exp
                            LEFT JOIN user_info AS co ON co.pk_id=exp.fk_user_id
                            LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
                          WHERE exp.fk_project_id=".$res['id']." ".$where_contribution."
                    ORDER BY int_start_date";
    //echo $contribution_sql;
    $contribution_result = mysql_query( $contribution_sql );
    $contribution_list = '';
    while( $contribution_res = mysql_fetch_assoc( $contribution_result ) ){
        $contribution_list = $contribution_list . $contribution_res['contribution'] . '<br>';
    }

    $list->set_row_data( 'contributions_list', $contribution_list);
  }


  if ($list->show_cols[$UPDATES_COL]) {

    $update_sql = "SELECT DATE_FORMAT(int_start_date, '%m/%d/%Y') as start_date,
                          status, notes, concern, resolution
                          FROM statusupdates AS exp
                          WHERE exp.fk_project_id=".$res['id']." ".$where_update."
                    ORDER BY int_start_date DESC";
    //echo $update_sql;
    $update_result = mysql_query( $update_sql );
    $update_list = '';
    while( $update_res = mysql_fetch_assoc( $update_result ) ){
    	
    	$icon = '';
     if( !$csv ){
        if ($update_res['concern']==0 || $update_res['status']==1) {
          $icon = '<img width="16px" height="16px" src="../img/icons/success_20.png">';
        } else {
          if ($update_res['concern']==1) {
            $icon = '<img width="16px" height="16px" src="../img/icons/warning.png">';
          } else {
            if ($update_res['concern']==2) {
              $icon = '<img width="16px" height="16px" src="../img/icons/error.png">';
            }
          }
        }
     }

    	 
    	$update_res['update'] .= $icon;
        $update_res['update'] .= '(' . $update_res['start_date'] . ') ';
        $update_res['update'] .= $LANG['concerns'][$update_res['concern']] . ', ';
        $update_res['update'] .= $update_res['notes'];
        if($update_res['status']==1){
          $update_res['update'] .= ', * Resolved *';
        } 

        $update_list = $update_list . $update_res['update'] . '<br>';
    }

    $list->set_row_data( 'updates_list', $update_list);
  }
  
  
  if ($list->show_cols[$DELIVERABLES_COL]) {
  
  	$deliverable_sql = "SELECT DATE_FORMAT(int_start_date, '%m/%d/%Y') as start_date,
                          clientinteraction, type, notes
                          FROM deliverables AS exp
                          WHERE exp.fk_project_id=".$res['id']." ".$where_deliverable."
                    ORDER BY int_start_date desc";
  	//echo $deliverable_sql;
  	$deliverable_result = mysql_query( $deliverable_sql );
  	$deliverable_list = '';
  	while( $deliverable_res = mysql_fetch_assoc( $deliverable_result ) ){
  		$deliverable_res['deliverable'] .= '(' . $deliverable_res['start_date'] . ') ';
  		$deliverable_res['deliverable'] .= $LANG['clientinteraction'][$deliverable_res['clientinteraction']] . ', ';
  		$deliverable_res['deliverable'] .= $LANG['deliverable_type'][$deliverable_res['type']] . ', ';
  		$deliverable_res['deliverable'] .= $deliverable_res['notes'];
  		$deliverable_list = $deliverable_list . $deliverable_res['deliverable'] . '<br>';
  	}
  
  	$list->set_row_data( 'deliverables_list', $deliverable_list);
  }  
  
  if ($list->show_cols[$CONFERENCES_COL]) {
  
  	$conference_sql = "SELECT name FROM conferences cnf
           				LEFT JOIN conference_projects AS icnf ON cnf.pk_id=icnf.fk_conference_id
  			             WHERE icnf.fk_project_id=".$res['id'];
  	$conference_result = mysql_query( $conference_sql );
  	$conference_list = '';
  	while( $conference_res = mysql_fetch_assoc( $conference_result ) ){
  		$conference_list = $conference_list . $conference_res['name'] . '<br>';
  	}
  
  	$list->set_row_data( 'conferences', $conference_list);
  }
  
}


$my_project->form->set_data( $search_data );


if( $search_string['all'] )
  $search_str = 'Showing all projects.';
elseif( $search_string )
  $search_str = 'Showing projects where: '.implode(' and ', $search_string ).'.';

$list->search_string = $search_str;
