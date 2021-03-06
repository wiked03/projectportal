<?
$SESS->require_login( $USER, PATH_SELF, 3 );
filter_page_vars( 1 );

load_model( 'deliverable' );
load_model( 'project' );
load_lib( 'email' );

$my_deliverable = new Deliverable( );
$my_project = new Project( );

//---------------------------------------------------
// try to load the deliverable or project data
if( preg_match( '/^c-([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_project->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error creating Deliverable, Project not found.' );

  $my_deliverable->set_data( array('fk_user_id'=>$USER->get( 'id' ), 'fk_project_id'=>$matches[1]) );

  $id = 'c-'.$matches[1];
}
elseif( preg_match( '/^([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_deliverable->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error: Deliverable not found.' );

  $id = $matches[1];
}
else
{
  $SESS->redirect_msg( 'failure', 'Error creating Deliverable: No project specified.' );
}

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  if( $form_data['submit_form'] == 3 ) {
  	$SESS->redirect('projects/view/'.$my_project->get('id') );
  } else {
  	
  	$deliverable_data = Array();
  	$deliverable_data['int_start_date'] = $form_data['int_start_date'];
  	$deliverable_data['fk_project_id'] = $matches[1];
  	
  	$deliverable_data['notes'] = $form_data['notes'];
  	$deliverable_data['clientinteraction'] = $form_data['clientinteraction'];
  	$deliverable_data['type'] = $form_data['type'];
  	
  	$my_deliverable = new Deliverable( );
  	$my_deliverable->set_data( $deliverable_data, F_HTM );
  	$my_deliverable->write_back( );
  	
  	if( $form_data['submit_form'] == 2 ) {
  		$SESS->set_redirect( 'admin/deliverables/add/c-'.$my_project->get('id') );
  	} else {
  		$SESS->set_redirect( 'projects/view/'.$my_project->get('id') );
  		$SESS->redirect_msg( 'success', 'Deliverable saved successfully.' );
  	}
  	
  }
  	  	
}


$view_data['form'] = $my_deliverable->form;

if( $my_deliverable->get( 'id' ) )
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

$PAGE->title      = $view_data['add_edit']." Deliverable";

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

load_view( 'admin/deliverables/add_deliverable', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
