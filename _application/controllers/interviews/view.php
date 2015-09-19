<?
$SESS->require_login( $USER, PATH_SELF );
filter_page_vars( 1 );

load_model( 'interview' );

$my_obj  = new Interview( 0, 1 );

if( !$my_obj->load_data( $PAGE->vars[0] ) )
{
  $SESS->set_message( array('type'=>'failure','text'=>'Interview not found.') );
  $SESS->redirect( 'home' );
}


// ---------------------------------------------------
$form_data = $_POST;
if( $form_data['submit_form'] == 'user_email' )
{
  //header( 'location: '.PATH_BASE.'interviews/email/'.$PAGE->vars[0].'/'.$form_data['user_id'] );
	header( 'location: '.PATH_BASE.'interviews/email/'.$PAGE->vars[0].'/'.$form_data['users'] );
  exit();
}


$view_data['form'] = &$my_obj->form;

$view_data['id'] = $my_obj->get( 'id' );

$my_view = new View();

$view_data['name_lock'] = false;
// if user is analyst, lock contact name
/*
if( $USER->get('level')<=2 && ($my_obj->get('fk_created_by_user') != $USER->get('id') && !$my_view->on_project( $USER->get('id'), $my_obj->get('fk_contact_id') )) )
{
  $view_data['name_lock'] = true;
}*/


//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = 'View Interview';

$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );


//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'interviews/view_interview', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?
load_view( 'popups/email_interview' );

//===========================================================================================
load_view( 'pagetail' );
  
?>
