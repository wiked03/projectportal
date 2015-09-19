<?
$SESS->require_login( $USER, PATH_SELF, 5 );

load_lib( 'list' );

global $LANG;


$form_data = $_POST;
if( $form_data['submit_form'] == 'user_del' && $USER->get('level') >= 5 )
{
  if( $form_data['user_id'] )
  {
    $sql = "DELETE FROM users WHERE pk_id=".$form_data['user_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'User deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the user.' );
}


// ---------------------------------------------------
// get the sorting info from page vars
$items_per_page = 25;

// get count from DB
$sql = "SELECT COUNT( DISTINCT pk_id ) AS total_users
        FROM user_info";
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total_users'];

// ---------------------------------------------------
$list = new CORE_List( $PAGE->var_string, $total, $items_per_page );

$list->no_footer = true;

$list->add_column( 'name', 'Name', 200 );
$list->add_column( 'username', 'User Name', 125 );
$list->add_column( 'level', 'Level', 100, 'center' );
$list->add_column( 'active', 'Active', 100, 'center' );
$list->add_column( 'actions', 'Actions', 100, 'center', true );


// ---------------------------------------------------
$sql = "SELECT *, CONCAT_WS( ', ',last_name,first_name) AS name, ui.pk_id AS id, COUNT( DISTINCT i.pk_id ) AS int_count, COUNT( DISTINCT p.pk_id ) AS proj_count
        FROM user_info AS ui
          LEFT JOIN users AS u ON u.pk_id=ui.pk_id
          LEFT JOIN projects AS p ON ui.pk_id=p.fk_pm_id
          LEFT JOIN interviews AS i ON ui.pk_id=i.fk_user_id
        GROUP BY id
        ORDER BY ".$list->get_order_by()."
        LIMIT ".$list->get_limit();

$result = mysql_query( $sql );


while( $res = mysql_fetch_assoc( $result ) )
{

  $res['level'] = $LANG['user_types'][ $res['level'] ];
  $res['active'] = $LANG['yes'][ $res['active'] ];

  $list->add_row( $res );

  $list->set_row_data( 'actions', '<a ><img src="img/icons/view.png"/></a><a ><img src="img/icons/pencil.png"/></a><a ><img src="img/icons/trash.png"/></a>' );

  $list->set_row_link( 'admin/users/edit/'.$res['id'] );
  $list->disable_row_link( 'actions' );

  $email = '';
  

  $val = '<a href="admin/users/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a>';
  $val .= '<a href="user/change_password/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/key.png"/></a>';
  if( $res['temp'] )
    $val .= '<a href="admin/users/email/'.$res['id'].'" title="send creation email"><img width="16" height="16" src="img/icons/email_go.png"/></a>';
  // allow to delete if no interviews or projects
  if( !$res['proj_count'] && !$res['int_count'] )
    $val .= '<a href="javascript:delete_user('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>';

  $list->set_row_data( 'actions', $val ); 

}

// Create user log record
$sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (7, ".$USER->get('id').", NOW())";
mysql_query( $sql );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "User List";

$PAGE->add_style( 'style_list.css' );

$PAGE->add_menu_item( 'Home', 'home', 'home' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">

  <h1>
    <img src="img/icons/big/group.png" />
    User List
    <a href="admin/users/edit" class="h_link img user_add">New User</a>
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
function delete_user( id )
{
  Dom.get( 'f_user_del-user_id' ).value = id;
  show( 'user_del_popup' );
}
</script> 

 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?


load_view( 'popups/user_delete' );

//===========================================================================================
load_view( 'pagetail' );
