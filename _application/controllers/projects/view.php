<?
$SESS->require_login( $USER, PATH_SELF, 2 );
filter_page_vars( 1 );

load_model( 'project' );

$my_obj  = new Project( 0, 1 );

if( !$my_obj->load_data( $PAGE->vars[0] ) )
{
  $SESS->set_message( array('type'=>'failure','text'=>'Project not found.') );
  $SESS->redirect( 'home' );
}

$view_data['form'] = &$my_obj->form;

$view_data['id'] = $my_obj->get( 'id' );

$view_data['edit_lock'] = 1;
if( $USER->get('level') <= 2  )
  $view_data['edit_lock'] = 0;

//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $my_obj->get('name');

$PAGE->add_script( 'jquery-1.9.0.js' );
$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );
$PAGE->add_style( 'style_list.css' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'projects/view_project', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>
