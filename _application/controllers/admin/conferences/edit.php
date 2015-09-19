<?
$SESS->require_login( $USER, PATH_SELF, 5 );
filter_page_vars( 1 );

load_model( 'conference' );

$my_conference = new Conference( );
$my_conference->load_data( $PAGE->vars[0] );

$form_data = $_POST;


if( $form_data['submit_form'] )
{
  $form_data['imported'] = 0;

  $my_conference->set_data( $form_data, F_HTM );

  $conference_id = $my_conference->write_back( );

  if( !$conference_id )
  {
    $SESS->set_message( 'failure', 'A conference with that name already exists.' );
  }
  else
  {    
    $SESS->redirect_msg( 'success', 'Conference saved successfully.' );
  }
}


$view_data['form'] = &$my_conference->form;

if( $my_conference->get( 'id' ) )
  $view_data['add_edit'] = 'Edit';
else
  $view_data['add_edit'] = 'New';

$view_data['id'] = $my_conference->get( 'id' );

$view_data['edit_lock'] = 1;

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $view_data['add_edit']." Conference";

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

load_view( 'admin/conferences/edit_conference', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>