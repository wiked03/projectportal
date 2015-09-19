<?
$SESS->require_login( $USER, PATH_SELF, 2 );
filter_page_vars( 1 );

load_model( 'contact' );
load_model( 'organization' );
load_model( 'project' );

$my_obj = new Contact( 0, 1 );
$my_org = new Organization( );
$my_prj = new Project( );

$my_obj->load_data( $PAGE->vars[0] );

/*
 * AP: For Drew's request now all the analyst can edit all the sources.
// if user is analyst, don't allow to edit unless he created it.
if( $USER->get('level')<=2 && $my_obj->get('fk_created_by_user') != $USER->get('id') )
{
  $SESS->redirect_msg( 'failure', 'User is unable to edit source.' );
}
*/

// Don't allow to edit Personal Sources
if( $USER->get('level')<=2 && $my_obj->get('is_source') == 3 )
{
  $SESS->redirect_msg( 'failure', 'Cannot edit Contact, Contact is a Personal Source.' );
}

//---------------------------------------------------
// write back data
$form_data = $_POST;

if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  // anonymous source handling
  if( $form_data['is_source'] == 3 )
  {
    $form_data['first_name'] = "Personal";
    $form_data['last_name'] = "Source";
    $form_data['recontact'] = 0;
  }

  if( is_array( $form_data['org_keys'] ) )
  {
    foreach( $form_data['org_keys'] as $key => $val )
    {

      // First, add any newly created organizations
      if( !CORE_is_num( $form_data['contact_orgs'][$key], F_NUM_UINT ) )
      {
        $my_org->set_data( array('name'=>$form_data['contact_orgs'][$key]), F_HTM );
        $form_data['contact_orgs'][$key] = $my_org->check_for_duplicate( 1 );
      }

      if(is_null($form_data['org_is_current'])){
        $org_is_current_array = array();
      } else {
        $org_is_current_array = $form_data['org_is_current'];
      }

      // set the contact_org info
      $form_data['orgs'][] = array( 'fk_organization_id'  => $form_data['contact_orgs'][$key],
                                    'city'                => $form_data['city'][$key],
                                    'state'               => $form_data['state'][$key],
                                    'country'             => $form_data['country'][$key],
                                    'is_primary'          => ($form_data['org_is_primary']==$val ? 1 : 0),
                                    'is_current'          => (in_array($val, $org_is_current_array) ? 1 : 0),
      		                        'zipcode'             => $form_data['zipcode'][$key], );
    }
  }
  
  if( is_array( $form_data['prj_keys'] ) )
  {
    foreach( $form_data['prj_keys'] as $key => $val )
    {
      // First, add any newly created projects
      if( !CORE_is_num( $form_data['contact_prjs'][$key], F_NUM_UINT ) )
      {
        $my_prj->set_data( array('name'=>$form_data['contact_prjs'][$key]), F_HTM );
        $form_data['contact_prjs'][$key] = $my_prj->check_for_duplicate( 1 );
      }
      // set the contact_prj info
      $form_data['prjs'][] = array( 'fk_project_id'  => $form_data['contact_prjs'][$key] );
    }
  }  

//print_r( $form_data );
//exit();
  $my_obj->set_data( $form_data, F_HTM );
  $my_obj->write_back( );

  if( $form_data['submit_form'] == 2 )
    $SESS->set_redirect( 'interviews/edit/c-'.$my_obj->get('id') );
  elseif( $form_data['submit_form'] == 3 )
    $SESS->set_redirect( 'activities/edit/c-'.$my_obj->get('id') );

  $SESS->redirect_msg( 'success', 'Source saved successfully. <a href="contacts/view/'.$my_obj->get('id').'">View Source</a>' );
}

//---------------------------------------------------
// set view data
$view_data['form'] = &$my_obj->form;

if( $my_obj->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $my_obj->get( 'id' );

//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Source";

$PAGE->add_script( 'jquery-1.9.0.js' );
$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );

$PAGE->no_redirect = true;
//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'contacts/edit_contact', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?
load_view( 'popups/definitions' );

//===========================================================================================
load_view( 'pagetail' );
  
?>
