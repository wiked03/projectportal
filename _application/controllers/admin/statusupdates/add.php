<?
$SESS->require_login( $USER, PATH_SELF, 3 );
filter_page_vars( 1 );

load_model( 'statusupdate' );
load_model( 'project' );
load_lib( 'email' );

$my_statusupdate = new StatusUpdate( );
$my_project = new Project( );

//---------------------------------------------------
// try to load the statusupdate or project data
if( preg_match( '/^c-([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_project->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error creating Status Update, Project not found.' );

  $my_statusupdate->set_data( array('fk_user_id'=>$USER->get( 'id' ), 'fk_project_id'=>$matches[1]) );

  $id = 'c-'.$matches[1];
}
elseif( preg_match( '/^([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_statusupdate->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error: Status Update not found.' );

  $id = $matches[1];
}
else
{
  $SESS->redirect_msg( 'failure', 'Error creating Status Update: No project specified.' );
}

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $statusupdate_data = Array();
  $statusupdate_data['int_start_date'] = $form_data['int_start_date'];
  $statusupdate_data['fk_project_id'] = $matches[1];

  /*
  $user_or_contractor_id = $form_data[$fk_user_id];
  if( preg_match( '/^u_([\d]*)$/', $user_or_contractor_id, $u_or_c_match ) )
  {
    $statusupdate_data['fk_user_id'] = $u_or_c_match[1];
  } elseif( preg_match( '/^c_([\d]*)$/', $user_or_contractor_id, $u_or_c_match ) )
  {
    $statusupdate_data['fk_contractor_id'] = $u_or_c_match[1];
  }*/

  $statusupdate_data['concern'] = $form_data['concern'];
  $statusupdate_data['notes'] = $form_data['notes'];
  $statusupdate_data['status'] = $form_data['status'];
  $statusupdate_data['resolution'] = $form_data['resolution'];

  $my_statusupdate = new StatusUpdate( );
  $my_statusupdate->set_data( $statusupdate_data, F_HTM );
  $my_statusupdate->write_back( );


  $SESS->redirect_msg( 'success', 'Status Update saved successfully.' );
}


$view_data['form'] = $my_statusupdate->form;

if( $my_statusupdate->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $id;

if( $USER->get('level')>=3) {
	$view_data['edit_lock'] = 1;
} else {
	$view_data['edit_lock'] = 0;
}

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Status Update";

$PAGE->add_script( 'jquery-1.9.0.js' );
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

load_view( 'admin/statusupdates/add_statusupdate', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
