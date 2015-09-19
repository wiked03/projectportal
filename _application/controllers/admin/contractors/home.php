<?
$SESS->require_login( $USER, PATH_SELF, 5 );

load_lib( 'list' );

global $LANG;


$form_data = $_POST;
if( $form_data['submit_form'] == 'user_del' && $USER->get('level') >= 5 )
{
  if( $form_data['user_id'] )
  {
    $sql = "DELETE FROM contractors WHERE pk_id=".$form_data['user_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Contractor deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the contractor.' );
}


// ---------------------------------------------------
// get the sorting info from page vars
$items_per_page = 25;

// get count from DB
$sql = "SELECT COUNT( DISTINCT pk_id ) AS total_users
        FROM contractors";
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total_users'];

// ---------------------------------------------------
$list = new CORE_List( $PAGE->var_string, $total, $items_per_page );

$list->no_footer = true;

$list->add_column( 'name', 'Name', 250 );
$list->add_column( 'email', 'Email', 150 );
$list->add_column( 'active', 'Active', 100, 'center' );
$list->add_column( 'actions', 'Actions', 100, 'center', true );


// ---------------------------------------------------
$sql = "SELECT *, name, con.pk_id AS id
        FROM contractors AS con
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();

$result = mysql_query( $sql );


while( $res = mysql_fetch_assoc( $result ) )
{

  $res['level'] = $LANG['user_types'][ $res['level'] ];
  $res['active'] = $LANG['yes'][ $res['active'] ];

  $list->add_row( $res );

  $list->set_row_data( 'actions', '<a ><img src="img/icons/view.png"/></a><a ><img src="img/icons/pencil.png"/></a><a ><img src="img/icons/trash.png"/></a>' );

  $list->set_row_link( 'admin/contractors/edit/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  $val = '<a href="admin/contractors/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
  $val .= '<a href="javascript:delete_contractor('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 

}



//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Contractor List";

$PAGE->add_style( 'style_list.css' );

$PAGE->add_menu_item( 'Home', 'home', 'home' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">

  <h1>
    <img src="img/icons/big/group.png" />
    Contractor List
    <a href="admin/contractors/edit" class="h_link img user_add">New Contractor</a>
  </h1>
<? 
//------------------------------------------------------------ 

echo $list->print_pagination( );
echo $list->print_table( );
echo SP_DIV;
echo $list->print_pagination( );

//------------------------------------------------------------
?>
 </div> 

<script type="text/javascript">
function delete_contractor( id )
{
  Dom.get( 'f_user_del-user_id' ).value = id;
  show( 'user_del_popup' );
}
</script> 

 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?


load_view( 'popups/contractor_delete' );

//===========================================================================================
load_view( 'pagetail' );
