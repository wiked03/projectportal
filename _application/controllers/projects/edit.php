<?
$SESS->require_login( $USER, PATH_SELF, 3 );
filter_page_vars( 1 );

$PAGE->add_script( 'jquery-1.9.0.js' );

load_model( 'project' );
load_model( 'contact' );
load_model( 'organization' );


$my_proj    = new Project( 0, 0 );
$my_contact = new Contact( );
$my_org     = new Organization( );
$my_org2     = new Organization( );
$my_view    = new View( );


$my_proj->load_data( $PAGE->vars[0] );
$my_proj->create_form( );

$form_data = $_POST;

if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  // find the organization, or write it
  $my_org->set_data( array('name'=>$form_data['org_search']), F_HTM );
  $form_data['fk_client_id'] = $my_org->check_for_duplicate( 1 );

  /*
  // find the organization, or write it (target)
  $my_org2->set_data( array('name'=>$form_data['org_search_target']), F_HTM );
  $form_data['fk_target_id'] = $my_org2->check_for_duplicate( 1 );
  */

  $form_data['fk_poc_id'] = $form_data['poc_search_val'];

  if( !$form_data['poc_search_val'] && $form_data['poc_search'] )
  {
    preg_match( '/(.*?)(?: |$)(.*)/', $form_data['poc_search'], $matches );
    $contact_data['first_name'] = $matches[1];
    $contact_data['last_name']  = $matches[2];

    if( $matches[2] == '')
      $contact_data['last_name'] = '?';

    $my_contact->set_data( $contact_data );
    $form_data['fk_poc_id'] = $my_contact->write_back( );
  }
  if( !$form_data['fk_poc_id'] )
    $form_data['fk_poc_id'] = NULL;

  $my_proj->set_data( $form_data, F_HTM );

  if( !$my_proj->write_back( ) )
  {
    $my_proj->form->set_error( 'name', E_VALUE_NOT_UNIQUE );

    $SESS->set_message( 'failure', 'There is already a Project with that name.' );
  }
  else
    $SESS->redirect_msg( 'success', 'Project saved successfully.');
}


$my_proj->set( 'org_search', $my_view->get( 'org_name', $my_proj->get('fk_client_id'), F_PHP ) );
$my_proj->set( 'org_search_target', $my_view->get( 'org_name', $my_proj->get('fk_target_id'), F_PHP ) );
$my_proj->set( 'poc_search', $my_view->get( 'contact',  $my_proj->get('fk_poc_id'), F_PHP ) );
$my_proj->set( 'poc_search_val', $my_proj->get('fk_poc_id') );


$view_data['form'] = &$my_proj->form;

$view_data['id'] = $my_proj->get( 'id' );

if( $my_proj->get( 'id' ) ){
  $view_data['add_edit'] = 'Edit';
  if($my_proj->get( 'prefix' )>0){
  	$view_data['id_prefix'] = sprintf('%s-%06d', $LANG['prefix'][ $my_proj->get( 'prefix' ) ], $view_data['id'] );
  } else {
  	$view_data['id_prefix'] = sprintf('   %06d', $view_data['id'] );
  }
} else {
  $view_data['add_edit'] = 'New';
}

//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Project";

$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'calendar.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );

$PAGE->add_style( 'style_forms.css' );
$PAGE->add_style( 'style_mini_cal.css' );

$PAGE->no_redirect = true;
//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'projects/edit_project', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
