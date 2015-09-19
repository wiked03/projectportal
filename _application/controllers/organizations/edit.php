<?
$SESS->require_login( $USER, PATH_SELF );
filter_page_vars( 1 );

load_model( 'organization' );

$my_org = new Organization( );

$my_org->load_data( $PAGE->vars[0] );

$form_data = $_POST;

if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $my_org->set_data( $form_data, F_HTM );

  if( !$my_org->write_back( ) )
  {
    $my_org->form->set_error( 'name', E_VALUE_NOT_UNIQUE );

    $SESS->set_message( 'failure', 'There is already an Organization with that name.' );
  }
  else
    $SESS->redirect_msg( 'success', 'Organization saved successfully. <a href="organizations/view/'.$my_org->get('id').'">View Organization</a>' );

}


$view_data['form'] = &$my_org->form;

if( $my_org->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $my_org->get( 'id' );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Organization";

$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );

$PAGE->no_redirect = true;
//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'organizations/edit_org', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>