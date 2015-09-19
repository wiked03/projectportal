<?

$SESS->require_login( $USER, PATH_SELF, 2 );

load_lib( 'list' );


$form_data = $_POST;
if( $form_data['submit_form'] == 'org_del' && $USER->get('level') >= 5 )
{
  if( $form_data['org_id'] )
  {
    $sql = "DELETE FROM organizations WHERE pk_id=".$form_data['org_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Organization deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the organization.' );
}


// ---------------------------------------------------
// get the sorting info from page vars
$items_per_page = 25;
$total_pages = 8;


// ---------------------------------------------------
$from = "FROM organizations AS o
           LEFT JOIN contact_orgs AS co ON o.pk_id=co.fk_organization_id
           LEFT JOIN projects AS p ON o.pk_id=p.fk_client_id";

// ---------------------------------------------------
// get count from DB
$sql = "SELECT COUNT( DISTINCT o.pk_id ) AS total
        ".$from."
        ".$where;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

// ---------------------------------------------------
$list = new CORE_List( $PAGE->var_string, $total, $items_per_page, $total_pages );

$list->no_footer = true;

$list->add_column( 'name', 'Organization Name', 200 );
$list->add_column( 'address', 'Address', 200 );
$list->add_column( 'city', 'City', 60 );
$list->add_column( 'state', 'State', 30 );
$list->add_column( 'zipcode', 'Zipcode', 30 );

$list->add_column( 'notes', 'Notes', 150 );
$list->add_column( 'contact_count', 'Sources',   55, 'right' );

if( $USER->get('level') < 5 )
  $list->add_column( 'actions',      'Actions',     50, 'center', true );
else
  $list->add_column( 'actions',      'Actions',     75, 'center', true );

// ---------------------------------------------------
// run query
$sql = "SELECT o.pk_id AS id, o.*, COUNT( DISTINCT co.fk_contact_id ) AS contact_count, COUNT( DISTINCT p.pk_id ) AS proj_count
        ".$from."
        ".$where."
        GROUP BY o.pk_id
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();

//echo $sql;

$result = mysql_query( $sql );


while( $res = mysql_fetch_assoc( $result ) )
{
  $list->add_row( $res );
  $list->set_row_link( 'organizations/view/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  $val = '<a href="organizations/view/'.$res['id'].'" title="View"><img width="16" height="16" src="img/icons/building.png"/></a>'.
                                  '<a href="organizations/edit/'.$res['id'].'" title="Edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
  if( $USER->get('level') >=5 && !$res['proj_count'] && !$res['contact_count'] )
    $val .= '<a href="javascript:delete_org('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 
}

// Create user log record
$sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (5, ".$USER->get('id').", NOW())";
mysql_query( $sql );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Organization List";

$PAGE->add_style( 'style_list.css' );

$PAGE->add_menu_item( 'Home', 'home', 'home' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">

  <h1>
    <img src="img/icons/big/building.png" />
    Organization List
    <a href="organizations/edit" class="h_link img org_add">New Organization</a>
  </h1>
<? 
//------------------------------------------------------------ 
?>
<div class="list_frame">
<?
echo $list->print_pagination( );
echo $list->print_table( );
echo SP_DIV;
echo $list->print_pagination( );

//------------------------------------------------------------
?>
</div>
 </div> 

<script type="text/javascript">
function delete_org( id )
{
  Dom.get( 'f_org_del-org_id' ).value = id;
  show( 'org_del_popup' );
}
</script>

 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?

if( $USER->get('level') >= 5  )
  load_view( 'popups/org_delete' );

//===========================================================================================
load_view( 'pagetail' );
