<?
$SESS->require_login( $USER, PATH_SELF, 2 );

load_lib( 'list' );
load_model( 'project' );
load_model( 'favorite' );

$items_per_page = 25;
$number_of_pages = 8;

// ---------------------------------------------------
$form_data = $_POST;
if( $form_data['submit_form'] == 'proj_del' && $USER->get('level') >= 5 )
{
  if( $form_data['proj_id'] )
  {
    $sql = "DELETE FROM projects WHERE pk_id=".$form_data['proj_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Project deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the Project.' );
}

// ---------------------------------------------------
if( $PAGE->vars[0] == 'print' || $PAGE->vars[0] == 'export' )
{
  $items_per_page = 0;
  $view_data['csv'] = true;
}

// ---------------------------------------------------
// get the sorting info from page vars
$view_data['var_string'] = $PAGE->var_string;
$view_data['type'] = 'Project';

$my_project = new Project(); 
$list = new CORE_List( $PAGE->var_string, 0, $items_per_page, $number_of_pages, 0, true );

$view_data['list'] = &$list;
$view_data['my_project'] = &$my_project;

load_view( 'projects/list_project', $view_data );

//$show_list = true;

// ---------------------------------------------------
$view_data['form'] = &$my_project->form;

$my_project->form->add_input( 'org_search', 'Organization' );

if( $list->search_string )
{
  $view_data['search_string'] = $list->search_string.' (Total results: '.$list->total.')';
  $show_list = true;
}

$view_data['path'] = 'projects';

// if print or export, load the correct view
if( $PAGE->vars[0] == 'print' || $PAGE->vars[0] == 'export' )
{
  load_view( $PAGE->vars[0], $view_data );
  exit();
}


// ---------------------------------------------------
// add favorites
$form_data = $_POST;
if( $form_data['submit_form'] == 'fave_add' )
{
  $my_fav = new Favorite( );
  $form_data['link'] = 'projects'.preg_replace( '/\/p-([\d]+)/', '', $PAGE->var_string );
  $form_data['type'] = 1;
  $my_fav->set_data( $form_data, F_HTM );

  if( $my_fav->write_back( ) )
    $SESS->set_message( 'success', 'Favorite saved successfully.' );
  else
    $SESS->set_message( 'failure', 'There was a problem saving your favorite.' );
}


//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Projects Search";

$PAGE->add_style( 'style_list.css' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_style( 'style_mini_cal.css' );

$PAGE->add_script( 'jquery-1.9.0.js' );
$PAGE->add_script( 'calendar.js' );
$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );


$PAGE->add_menu_item( 'Home', 'home', 'home' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">

  <h1>
    <img src="img/icons/big/vcard.png" />
    Project Search
<?
 if( $show_list ) {
  echo '<a onclick="show(\'favorites_popup\')" class="h_link img star_add">Favorite</a>';
  echo '<a href="projects/export'.$PAGE->var_string.'" class="h_link img export">Export</a>';
 }
  echo '<a href="projects/edit" class="h_link img proj_add">New Project</a>';
?>
  </h1>
<? 

//------------------------------------------------------------ 

load_view( 'projects/search_project', $view_data );

if( $show_list )
{
?>
<div class="list_frame">
<?
echo $list->print_pagination( );
echo '<div class="table_frame">';
echo $list->print_table( );
echo SP_DIV;
echo '</div>';
echo $list->print_pagination( );

//------------------------------------------------------------
?>
</div>
<?
} else {
	// Create user log record
	$sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (6, ".$USER->get('id').", NOW())";
	mysql_query( $sql );
}
?>

<script type="text/javascript">
function delete_proj( id )
{
  Dom.get( 'f_proj_del-proj_id' ).value = id;
  show( 'project_del_popup' );
}
</script>

</div>  
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?

if( $USER->get('level') >= 5  )
  load_view( 'popups/project_delete' );

load_view( 'popups/favorites', $view_data );

//===========================================================================================
load_view( 'pagetail' );
