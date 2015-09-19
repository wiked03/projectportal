<?
$SESS->require_login( $USER, PATH_SELF, 5 );
filter_page_vars( 1 );

load_model( 'contractor' );
load_lib( 'email' );

$my_contractor = new Contractor( );

$my_contractor->load_data( $PAGE->vars[0] );

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $my_contractor->set_data( $form_data, F_HTM );

  $user_id = $my_contractor->write_back( );

  if( !$user_id )
  {
    $SESS->set_message( 'failure', 'A contractor with that name already exists.' );
  }
  else
  {
    $SESS->redirect_msg( 'success', 'Contractor saved successfully.' );
  }
}


$view_data['form'] = $my_contractor->form;

if( $my_contractor->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $my_contractor->get( 'id' );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Contractor";

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

load_view( 'admin/contractors/edit_contractor', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>