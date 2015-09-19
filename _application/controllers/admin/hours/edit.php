<?
$SESS->require_login( $USER, PATH_SELF, 5 );
filter_page_vars( 1 );

load_model( 'hour' );
load_model( 'project' );
load_lib( 'email' );

$my_hour = new Hour( );
$my_project = new Project( );

//---------------------------------------------------
// try to load the hour or project data
if( preg_match( '/^c-([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_project->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error creating Hour, Project not found.' );

  $my_hour->set_data( array('fk_user_id'=>$USER->get( 'id' ), 'fk_project_id'=>$matches[1]) );

  $id = 'c-'.$matches[1];
}
elseif( preg_match( '/^([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_hour->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error: Hour not found.' );

  $id = $matches[1];
}
else
{
  $SESS->redirect_msg( 'failure', 'Error creating Hour: No project specified.' );
}

//$my_hour->load_data( $PAGE->vars[0] );

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $my_hour->set_data( $form_data, F_HTM );

  $my_hour->write_back( );
  $SESS->redirect_msg( 'success', 'Hour saved successfully.' );
}


$view_data['form'] = $my_hour->form;

if( $my_hour->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $id;

if( $USER->get('level')>=5) {
	$view_data['edit_lock'] = 1;
} else {
	$view_data['edit_lock'] = 0;
}

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Hour";

$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );

$PAGE->add_script( 'calendar.js' );
$PAGE->add_style( 'style_mini_cal.css' );

$PAGE->no_redirect = true;
//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'admin/hours/edit_hour', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
