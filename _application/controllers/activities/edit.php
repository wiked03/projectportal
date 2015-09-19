<?

$SESS->require_login( $USER, PATH_SELF, 2 );
filter_page_vars( 1 );

load_model( 'interview' );
load_model( 'contact' );

$my_obj = new Interview( 0, 0 );
$my_contact = new Contact( );
$view = new View( );

//---------------------------------------------------
// try to load the interview or contact data
if( preg_match( '/^c-([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  if( !$my_contact->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error creating Activity, Source not found.' );

  $my_obj->set_data( array('fk_user_id'=>$USER->get( 'id' ), 'fk_contact_id'=>$matches[1]) );

  $id = 'c-'.$matches[1];
}
elseif( preg_match( '/^([\d]*)$/', $PAGE->vars[0], $matches ) )
{
  if( !$my_obj->load_data( $matches[1] ) )
    $SESS->redirect_msg( 'failure', 'Error: Activity not found.' );

  $id = $matches[1];
}
else
{
  $SESS->redirect_msg( 'failure', 'Error creating Activity: No Source specified.' );
}

$my_obj->create_form( );

//---------------------------------------------------
// write back data
$form_data = $_POST;

if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;
  $form_data['is_activity'] = 1;

  $my_obj->set_data( $form_data, F_HTM );
  $my_obj->write_back( );

  $my_contact->load_data( $my_obj->get( 'fk_contact_id' ) );
  
  if($my_contact->get('is_source') == 0)
    $my_contact->set( 'is_source', 1 );
  $my_contact->write_back( );
//print_r( $my_obj );
//exit;
  $SESS->redirect_msg( 'success', 'Activity saved successfully.' );
}

//---------------------------------------------------
// set view data
$view_data['form'] = &$my_obj->form;

if( $my_obj->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $id;

$view_data['user'] = $view->get( 'user', $my_obj->get( 'fk_user_id' ) );
$view_data['contact'] = $view->get( 'contact', $my_obj->get( 'fk_contact_id' ) );

$view_data['edit_lock'] = 1;

// if user is analyst, lock interview for editing if after one week
if( $USER->get('level')<=2 && ( $my_obj->get('id') 
     && $my_obj->get('created') < date( 'Y-m-d', time()-(60*60*24)*7 )
     || $my_obj->get('fk_created_by_user') != $USER->get('id') ) )
{
  $view_data['edit_lock'] = 0;
}


//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Activity";

$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_script( 'calendar.js' );

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

load_view( 'activities/edit_activity', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>