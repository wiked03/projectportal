<?
$SESS->require_login( $USER, PATH_SELF, 2 );

load_lib( 'list' );



$form_data = $_POST;
if( $form_data['submit_form'] == 'fave_del' )
{
  if( $form_data['fave_id'] )
  {
    $sql = "DELETE FROM favorites WHERE pk_id=".$form_data['fave_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Favorite deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem removing your favorite.' );
}





$PAGE->title = "Home";
filter_page_vars( 0 );


$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_style( 'style_list' );

load_view( 'pagehead' );
// -----------------------------------------------------------------------------------------

?>
<div class="page_content">
  <div class="page_content_full">
<? //------------------------------------------------------------ ?>

   <h1>Home</h1>

<div style="width:420px;float:left;">
<?

//<a href="interviews" class="hilight img interviews">Interviews</a><br/>
//<a href="/interviews/dr-lw" class="hilight img interviews">Interviews in the Last Week</a><br/>
//<a href="/interviews/dr-lm" class="hilight img interviews">Interviews in the Last Month</a><br/><br/>

if( $USER->get('level') >= 2 )
{
  echo '<h2>Pages</h2>
<a href="contacts" class="hilight img contacts">Sources</a><br/>
<a href="contacts/edit" class="hilight img contact_add">New Source</a><br/><br/>	

<a href="organizations" class="hilight img org">Organizations</a><br/>
<a href="organizations/edit" class="hilight img org_add">New Organization</a><br/><br/>

<a href="projects" class="hilight img proj">Projects</a><br/>';
  
}

if( $USER->get('level') >= 3 )
  echo '<a href="projects/edit" class="hilight img proj_add">New Project</a><br/><br/>';

echo '<a href="user/change_password" class="hilight img contacts">Change Password</a><br/><br/>';

if( $USER->get('level') >= 5 ) // admin
{
  echo '<h2>Administrative Functions</h2>
<a href="admin/users" class="hilight img user_list">User List</a><br/>
<a href="admin/users/edit" class="hilight img user_add">New User</a><br/>
<a href="admin/users/change" class="hilight img user_add">Act as a Different User</a><br/><br/>
<a href="admin/organizations/merge" class="hilight img org">Merge Organizations</a><br/>
<a href="admin/contacts/merge" class="hilight img contacts">Merge Sources</a><br/><br/>';
} else  { // all the other user

  echo '<br/>';
  if( $SESS->get_another_user_id() ) // this is the case when the admin switched to this user.
  {
    echo '<h2>Administrative Functions</h2>
  	<a href="admin/users/change" class="hilight img user_add">Act as a Different User</a><br/><br/>
	<a href="admin/contacts/merge" class="hilight img contacts">Merge Sources</a><br/><br/>';
  } else {
	echo '<h2>Administrative Functions</h2>
	<a href="admin/contacts/merge" class="hilight img contacts">Merge Sources</a><br/><br/>';
  }
}


?>

<?=SP_DIV?>
</div>
<div style="width:490px;float:left;">


<?


$form = new CORE_Form( 'f_search' );

$form->add_input( 'search', 'Search', NULL, 'search' );

echo '<h2>Search</h2>
 <p>Enter a source, project, organization name, or a contact name</p>';
echo '<form id="f_search" name="f_search">';
echo $form->print_item( 'search' );
echo '</form><br/><br/>';


$list = new CORE_List( $PAGE->var_string, 0, $items_per_page, $number_of_pages );
$list->set_total( $total );
$list->no_header = true;
$list->no_footer = true;

//$list->add_column( 'name',         'Name', 160 );
$list->add_column( 'link',      '', 400 );
$list->add_column( 'actions',   '', 24, 'center' );


$sql = "SELECT * FROM favorites WHERE fk_user_id=".$USER->get('id');
$result = mysql_query( $sql );
echo '<h2>Favorites</h2>';
while( $res = mysql_fetch_assoc( $result ) )
{
  if( $res['title'] == '' )
  {
    $res['title'] = CORE_decode( $res['link'], F_URI );
  }

  $list->add_row( $res );
  $list->set_row_link( $res['link'] );
  $list->disable_row_link( 'actions' );

  $res['link'] = '<a href="'.$res['link'].'" class="img star_'.$res['type'].'">'.$res['title'].'</a><br/>';
  $list->set_row_data( 'link', $res['link'] );

  $list->set_row_data( 'actions', 
    '<a href="javascript:delete_fave('.$res['pk_id'].');" title="delete"><img width="16" height="16" src="img/icons/star_delete.png"/></a>' ); 
}

echo $list->print_table( 'No Favorites found.' );

?>

<script type="text/javascript">
function delete_fave( id )
{
  Dom.get( 'f_fave_del-fave_id' ).value = id;
  show( 'favorites_del_popup' );
}
</script>


<?=SP_DIV?>
</div>


<? //------------------------------------------------------------ ?>
  </div>
</div> <!-- end of page_content -->


<script type="text/javascript">
var search_all = new Xhr_search( 'search_all', 'f_search-search', 'all' );

search_all._show = 'search_all_show("search_all", resp, "org")';
</script>


<?
load_view( 'popups/favorites_delete' );

load_view( 'pagetail' );


?>
