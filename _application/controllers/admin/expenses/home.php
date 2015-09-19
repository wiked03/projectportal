<?
$SESS->require_login( $USER, PATH_SELF, 3 );

load_lib( 'list' );

$view = new View( );

global $LANG;


$var_string = CORE_decode( $PAGE->var_string, F_URI );

$form_data = $_POST;
if( $form_data['submit_form'] == 'user_del' && $USER->get('level') >= 5 )
{
  if( $form_data['user_id'] )
  {
    $sql = "DELETE FROM expenses WHERE pk_id=".$form_data['user_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Expense deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the expense.' );
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

if( isset( $where ) )
{
  $where = "WHERE ".implode( " AND ", $where );
}

// ---------------------------------------------------
// get the sorting info from page vars
$items_per_page = 25;

// get count from DB
$sql = "SELECT COUNT( DISTINCT p.pk_id ) AS total_users
        FROM expenses as p " . $where;

$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total_users'];

// ---------------------------------------------------
$list = new CORE_List( $PAGE->var_string, $total, $items_per_page );

$list->no_footer = true;

$list->add_column( 'proj_name',    'Project', 205 );
$list->add_column( 'con_name',    'Contractor', 205 );
$list->add_column( 'int_amount', 'Amount', 75 );
$list->add_column( 'int_date', 'Date', 75 );
$list->add_column( 'actions', 'Actions', 100, 'center', true );


// ---------------------------------------------------
$sql = "SELECT *, ex.pk_id AS id, p.pk_id AS proj_id, p.name AS proj_name, 
              c.pk_id AS con_id, c.name AS con_name, 
              FORMAT(int_amount, 2) as int_amount 
        FROM expenses AS ex 
		  LEFT JOIN projects AS p ON p.pk_id = ex.fk_project_id
		  LEFT JOIN contractors AS c ON ex.fk_contractor_id = c.pk_id " . $where . "
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();

$result = mysql_query( $sql );


while( $res = mysql_fetch_assoc( $result ) )
{

  $res['level'] = $LANG['user_types'][ $res['level'] ];
  $res['active'] = $LANG['yes'][ $res['active'] ];

  $list->add_row( $res );
  
  $proj = CORE_encode( $res['proj_name'], F_HTM, F_SQL );
  if( $res['proj_id'] )
    $proj = '<a href="projects/view/'.$res['proj_id'].'">'.$proj.'</a>';
  $list->set_row_data( 'proj_name', $proj );
  
  $con = CORE_encode( $res['con_name'], F_HTM, F_SQL );
  if( $res['con_id'] )
    $con = '<a href="admin/contractors/view/'.$res['con_id'].'">'.$con.'</a>';
  $list->set_row_data( 'con_name', $con );  

  $list->set_row_data( 'actions', '<a ><img src="img/icons/view.png"/></a><a ><img src="img/icons/pencil.png"/></a><a ><img src="img/icons/trash.png"/></a>' );

  $list->set_row_link( 'admin/expenses/edit/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  $val = '<a href="admin/expenses/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
  $val .= '<a href="javascript:delete_expense('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 

}

if( $search_string['all'] )
  $search_str = 'Showing all expenses.';
elseif( $search_string )
  $search_str = 'Showing expenses where: '.implode(' and ', $search_string ).'.';

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Expenses List";

$PAGE->add_style( 'style_list.css' );

$PAGE->add_menu_item( 'Home', 'home', 'home' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">

  <h1>
    <img src="img/icons/big/group.png" />
    Expenses List
    <!--<a href="admin/contractors/edit" class="h_link img user_add">New Expense</a>-->
  </h1>
<? 
//------------------------------------------------------------ 

if( $search_str ){
  echo '<div class="search_text" id="interview_search_text">
<a onclick="hide(\'interview_search_text\');show(\'interview_search_form\');" class="hilight img search_add">Search</a>'.$search_str.'
 </div>';
}


echo $list->print_pagination( );
echo $list->print_table( );
echo SP_DIV;
echo $list->print_pagination( );

//------------------------------------------------------------
?>
 </div> 

<script type="text/javascript">
function delete_expense( id )
{
  Dom.get( 'f_user_del-user_id' ).value = id;
  show( 'user_del_popup' );
}
</script> 

 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?


load_view( 'popups/expense_delete' );

//===========================================================================================
load_view( 'pagetail' );
