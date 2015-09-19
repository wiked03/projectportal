<?

// set 'any' as default
$search_data['col'] = '0.1.2.3';

global $LANG, $REGEX, $USER;

$edit_lock = 1;
if( $USER->get('level') <= 5  )
  $edit_lock = 0;

$view = new View( );

$var_string = CORE_decode( $var_string, F_URI );

// Override default list of columns
if( $USER->get('userlog_defaults') ){
	$search_data['col'] = $USER->get('userlog_defaults');
}


// ============================================================================
// URL line search strings

// --------------------
// (na) name
if( preg_match( '/\/na-([^\/]+)/', $var_string, $matches ) )
{
  $where[] = "p.name LIKE '%".$matches[1]."%'";

  $search_data['name'] = $matches[1];
  $search_string[] = '<span class="label">Type</span> like "<span class="search_param">'.CORE_encode( $LANG['log_type'][$matches[1]], F_HTM ).'</span>"';
}
// --------------------
// (cb) created
if( preg_match( '/\/cb-([\d\.]+)($|\/)/', $var_string, $matches ) )
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
		$where[] = "p.fk_created_by_user IN (".implode( ',', $q_str ).") ";
		$search_data['created_by'] = implode( '.', $q_str );
		$search_string[] = '<span class="label">User</span> is '.implode( ' or ', $s_str );
	}
}
// --------------------
// (sd) start date
if( preg_match( '/\/sd-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  $my_date = CORE_date( $matches[1], G_DATE_FORMAT );
  $where[] = "p.created >= '".CORE_date( $matches[1], F_DATE_SQL)."'";

  $search_data['start'] = $my_date;
  $search_string[] = '<span class="label">Start Date</span> greater or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (ed) end date
if( preg_match( '/\/ed-([\d]{8})($|\/)/', $var_string, $matches ) )
{
  $my_date = CORE_date( $matches[1], G_DATE_FORMAT );
  $where[] = "p.created <= DATE_ADD('".CORE_date( $matches[1], F_DATE_SQL)."' , INTERVAL 1 DAY) ";
  
  $search_data['end'] = $matches[1];
  $search_string[] = '<span class="label">End Date</span> less or equal than "<span class="search_param">'.CORE_encode( $matches[1], F_HTM ).'</span>"';
}
// --------------------
// (all) all
if( preg_match( '/\/all($|\/)/', $var_string, $matches ) )
{
  $where[] = 1;
  $search_string['all'] = true;
}

if( isset( $where ) )
{
  $where = "WHERE ".implode( " AND ", $where );
  $show_list = true;
}

$from = "FROM userlogs AS p
           LEFT JOIN user_info AS um ON um.pk_id=p.fk_created_by_user";

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

$list->add_column( 'created',     'Timestamp', 200 ); // 1
$list->add_column( 'created_by',  'User', 200 ); // 2
$list->add_column( 'name',        'Activity Type', 400 ); // 3

//$list->required_columns( '0.1' );
$list->default_columns( $search_data['col'] );

// ---------------------------------------------------
// run query
$sql = "SELECT p.pk_id as id,
		       name,
		       concat(first_name, ' ', last_name) as created_by,
			   created
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
  $res['name'] = $LANG['log_type'][$res['name']];
  $list->add_row( $res );
  $list->set_row_link( 'userlogs/view/'.$res['id'] );
  $list->disable_row_link( 'actions' );  
}


$my_userlog->form->set_data( $search_data );


if( $search_string['all'] )
  $search_str = 'Showing all activities.';
elseif( $search_string )
  $search_str = 'Showing activities where: '.implode(' and ', $search_string ).'.';

$list->search_string = $search_str;
