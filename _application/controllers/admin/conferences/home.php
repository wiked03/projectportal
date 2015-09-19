<?
$SESS->require_login( $USER, PATH_SELF, 5 );

load_lib( 'list' );

global $LANG;


$form_data = $_POST;
if( $form_data['submit_form'] == 'conference_del' && $USER->get('level') >= 5 )
{
  if( $form_data['conference_id'] )
  {
    $sql = "DELETE FROM conferences WHERE pk_id=".$form_data['conference_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Conference deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the conference.' );
}


// ---------------------------------------------------
// get the sorting info from page vars
$items_per_page = 25;

// get count from DB
$sql = "SELECT COUNT( DISTINCT pk_id ) AS total_conferences
        FROM conferences";
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total_conferences'];

// ---------------------------------------------------
$list = new CORE_List( $PAGE->var_string, $total, $items_per_page );

$list->no_footer = true;

$list->add_column( 'name', 'Name', 150 );
$list->add_column( 'conference_date', 'Start Date', 60, 'center' );
$list->add_column( 'conference_end_date', 'End Date', 60, 'center' );
$list->add_column( 'location', 'Location', 80, 'center' );;
$list->add_column( 'active', 'Active', 50, 'center' );
$list->add_column( 'actions', 'Actions', 60, 'center', true );


// ---------------------------------------------------
$sql = "SELECT *, pk_id AS id,
           (SELECT group_concat(CONCAT_WS(' ', TRIM(ui.first_name), TRIM(ui.last_name)) SEPARATOR ', ') FROM conferences p2 
              LEFT JOIN user_conferences AS up ON up.fk_conference_id=p2.pk_id
              LEFT JOIN user_info AS ui ON up.fk_user_id = ui.pk_id
             where p2.pk_id = c.pk_id
              group by p2.pk_id) AS attendees
        FROM conferences AS c
        GROUP BY id
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();
//echo $sql;
$result = mysql_query( $sql );


while( $res = mysql_fetch_assoc( $result ) )
{
  $res['proj_count'] = 0;
  $res['active'] = $LANG['yes'][ $res['active'] ];

  $list->add_row( $res );

  $list->set_row_data( 'actions', '<a ><img src="img/icons/view.png"/></a><a ><img src="img/icons/pencil.png"/></a><a ><img src="img/icons/trash.png"/></a>' );

  $list->set_row_link( 'admin/conferences/edit/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  $email = '';
  

  $val = '<a href="admin/conferences/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
  if( $res['temp'] )
    $val .= '<a href="admin/conferences/email/'.$res['id'].'" title="send creation email"><img width="16" height="16" src="img/icons/email_go.png"/></a>';
  // allow to delete if no interviews or projects
  if( !$res['proj_count'] && !$res['int_count'] ) // TODO: Add this restriction for conferences linked to projects or interviews.
    $val .= '<a href="javascript:delete_conference('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 

}

// Create user log record
$sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (8, ".$USER->get('id').", NOW())";
mysql_query( $sql );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Conference List";

$PAGE->add_style( 'style_list.css' );

$PAGE->add_menu_item( 'Home', 'home', 'home' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">

  <h1>
    <img src="img/icons/big/group.png" />
    Conference List
    <a href="admin/conferences/edit" class="h_link img conference_add">New Conference</a>
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
function delete_conference( id )
{
  Dom.get( 'f_conference_del-conference_id' ).value = id;
  show( 'conference_del_popup' );
}
</script> 

 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?


load_view( 'popups/conference_delete' );

//===========================================================================================
load_view( 'pagetail' );
