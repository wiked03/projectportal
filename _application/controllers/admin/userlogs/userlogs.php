<?
$SESS->require_login( $USER, PATH_SELF, 5 );

load_lib( 'list' );
load_model( 'userlog' );
load_model( 'favorite' );

$items_per_page = 25;
$number_of_pages = 8;

// ---------------------------------------------------
if( $PAGE->vars[0] == 'print' || $PAGE->vars[0] == 'export' )
{
  $items_per_page = 0;
  $view_data['csv'] = true;
}

// ---------------------------------------------------
// get the sorting info from page vars
$view_data['var_string'] = $PAGE->var_string;
$view_data['type'] = 'Userlog';

$my_userlog = new Userlog(); 
$list = new CORE_List( $PAGE->var_string, 0, $items_per_page, $number_of_pages, 0, true );

$view_data['list'] = &$list;
$view_data['my_userlog'] = &$my_userlog;

load_view( 'admin/userlogs/list_userlog', $view_data );

//$show_list = true;

// ---------------------------------------------------
$view_data['form'] = &$my_userlog->form;

if( $list->search_string )
{
  $view_data['search_string'] = $list->search_string.' (Total results: '.$list->total.')';
  $show_list = true;
}

$view_data['path'] = 'userlogs';

// if print or export, load the correct view
if( $PAGE->vars[0] == 'print' || $PAGE->vars[0] == 'export' )
{
  load_view( $PAGE->vars[0], $view_data );
  exit();
}


// Create user log record
$sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (9, ".$USER->get('id').", NOW())";
mysql_query( $sql );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Activity Search";

$PAGE->add_style( 'style_list.css' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_style( 'style_mini_cal.css' );

$PAGE->add_script( 'jquery-1.9.0.js' );
$PAGE->add_script( 'date.format.js' );
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
    Activity Search
<?
 if( $show_list ) {
  echo '<a onclick="show(\'favorites_popup\')" class="h_link img star_add">Favorite</a>';
  echo '<a href="userlogs/export'.$PAGE->var_string.'" class="h_link img export">Export</a>';
 }
  //echo '<a href="userlogs/edit" class="h_link img proj_add">New Userlog</a>';
?>
  </h1>
<? 

//------------------------------------------------------------ 

load_view( 'admin/userlogs/search_userlog', $view_data );

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
}
?>

<script type="text/javascript">
/*
function delete_proj( id )
{
  Dom.get( 'f_proj_del-proj_id' ).value = id;
  show( 'userlog_del_popup' );
}*/
</script>

</div>  
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->
<?

//if( $USER->get('level') >= 5  )
//  load_view( 'popups/userlog_delete' );

load_view( 'popups/favorites', $view_data );

//===========================================================================================
load_view( 'pagetail' );
