<?
$SESS->require_login( $USER, PATH_SELF );
filter_page_vars( 1 );

load_model( 'organization' );

$my_org = new Organization( 0, 1 );

if( !$my_org->load_data( $PAGE->vars[0] ) )
{
  $SESS->set_message( array('type'=>'failure','text'=>'Organization not found.') );
  $SESS->redirect( 'home' );
}


$view_data['form'] = &$my_org->form;

$view_data['id'] = $my_org->get( 'id' );

//-------------------------------------------------------------------------------------------
// Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = $my_org->get( 'name' );

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

load_view( 'organizations/view_org', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
  
?>