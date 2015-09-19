<?
$SESS->require_login( $USER, PATH_SELF, 3 );
filter_page_vars( 1 );

load_model( 'resource' );
load_model( 'project' );
load_lib( 'email' );

$my_resource = new Resource( );
$my_project = new Project( );

//---------------------------------------------------
// try to load the resource or project data
if( preg_match( '/^c-([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_project->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error creating Resource, Project not found.' );

  $my_resource->set_data( array('fk_user_id'=>$USER->get( 'id' ), 'fk_project_id'=>$matches[1]) );

  $id = 'c-'.$matches[1];
}
elseif( preg_match( '/^([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  
  if( !$my_resource->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error: Resource not found.' );

  $id = $matches[1];
}
else
{
  $SESS->redirect_msg( 'failure', 'Error creating Resource: No project specified.' );
}

//$my_resource->load_data( $PAGE->vars[0] );

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $counter = 0;
  while(true){

    $fk_user_id = 'fk_user_id_' . $counter;
    if (!array_key_exists($fk_user_id, $form_data) 
          || $form_data[$fk_user_id] == '0'){
      break;
    }

    $resource_data = Array();
    $resource_data['int_start_date'] = $form_data['int_start_date'];
    $resource_data['int_end_date'] = $form_data['int_end_date'];
    $resource_data['fk_project_id'] = $matches[1];

    $user_or_contractor_id = $form_data[$fk_user_id];
    if( preg_match( '/^u_([\d]*)$/', $user_or_contractor_id, $u_or_c_match ) )
    {
      $resource_data['fk_user_id'] = $u_or_c_match[1];
    } elseif( preg_match( '/^c_([\d]*)$/', $user_or_contractor_id, $u_or_c_match ) )
    {
      $resource_data['fk_contractor_id'] = $u_or_c_match[1];
    }
    //$resource_data['fk_user_id'] = $form_data[$fk_user_id];
    $resource_data['effort'] = $form_data['effort_' . $counter];
    $resource_data['notes'] = $form_data['notes_' . $counter];
    $counter = $counter + 1;

    $my_resource = new Resource( );
    //$my_resource->set_data( array('fk_user_id'=>$USER->get( 'id' ), 'fk_project_id'=>$matches[1]) );
    $my_resource->set_data( $resource_data, F_HTM );
    $my_resource->write_back( );
  }

  $SESS->redirect_msg( 'success', 'Contribution saved successfully.' );
}


$view_data['form'] = $my_resource->form;

if( $my_resource->get( 'id' ) )
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

$PAGE->title      = $view_data['add_edit']." Contribution";

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

load_view( 'admin/resources/add_resource', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
