<?
$SESS->require_login( $USER, PATH_SELF );
filter_page_vars( 1 );

load_model( 'interview' );

$my_obj  = new Interview( 0, 1 );

if( !$my_obj->load_data( $PAGE->vars[0] ) )
{
  $SESS->set_message( array('type'=>'failure','text'=>'Activity not found.') );
  $SESS->redirect( 'home' );
}

$view_data['form'] = &$my_obj->form;

$view_data['id'] = $my_obj->get( 'id' );

//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = 'View Activity';

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

load_view( 'activities/view_activity', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>