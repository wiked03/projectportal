<?
$SESS->require_login( $USER, PATH_SELF, 2 );
filter_page_vars( 1 );

load_model( 'contact' );

$my_contact = new Contact( 0, 1 );

if( !$my_contact->load_data( $PAGE->vars[0] ) )
{
  $SESS->set_message( array('type'=>'failure','text'=>'Source not found.') );
  $SESS->redirect( 'home' );
}

// ---------------------------------------------------
$form_data = $_POST;
if( $form_data['submit_form'] == 'user_email' )
{
	//error_log(print_r($_POST, true));
	header( 'location: '.PATH_BASE.'interviews/email/'.$form_data['interview_id'].'/'.$form_data['users'] );
	exit();
}


//
if( $USER->get('level')<=2 && ($my_contact->get('fk_created_by_user') != $USER->get('id') && $my_contact->get('is_source') == 3 ) )
{
  $SESS->redirect_msg( 'failure', 'Cannot view Contact, Contact is a Personal Source.' );
}


// ---------------------------------------------------
$form_data = $_POST;
if( $form_data['submit_form'] == 'int_del' && $USER->get('level') >= 5 )
{
  if( $form_data['int_id'] )
  {
    $sql = "DELETE FROM interviews WHERE pk_id=".$form_data['int_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Interview deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the interview.' );
}
if( $form_data['submit_form'] == 'act_del' && $USER->get('level') >= 5 )
{
  if( $form_data['act_id'] )
  {
    $sql = "DELETE FROM interviews WHERE pk_id=".$form_data['act_id'];
    mysql_query( $sql );
    $SESS->redirect_msg( 'success', 'Activity deleted successfully.' );
  }
  else
    $SESS->redirect_msg( 'failure', 'There was a problem deleting the activity.' );
}

if( $form_data['submit_form'] == 'contact_org_del' )
{
	if( $form_data['contact_id'] && $form_data['org_id'] )
	{
		$sql = "DELETE FROM contact_orgs where fk_contact_id = ".$form_data['contact_id']." AND fk_organization_id = ".$form_data['org_id'];
	    mysql_query( $sql );
	    $SESS->redirect_msg( 'success', 'Organization removed successfully.' );
	}
	else
		$SESS->redirect_msg( 'failure', 'There was a problem removing the organization.' );
}

if( $form_data['submit_form'] == 'contact_prj_del' )
{
	if( $form_data['contact_id'] && $form_data['prj_id'] )
	{
		$sql = "DELETE FROM contact_projects where fk_contact_id = ".$form_data['contact_id']." AND fk_project_id = ".$form_data['prj_id'];
		mysql_query( $sql );
		$SESS->redirect_msg( 'success', 'project removed successfully.' );
	}
	else
		$SESS->redirect_msg( 'failure', 'There was a problem removing the project.' );
}

$my_view = new View();

// if user is analyst, lock contact name
/*
if( $USER->get('level')<=2 && ($my_contact->get('fk_created_by_user') != $USER->get('id') && !$my_view->on_project( $USER->get('id'), $my_contact->get('id') )) )
{
  $view_data['name_lock'] = true;
}*/
if( $USER->get('level')<=2 && $my_contact->get('is_source') == 3 )
{
  $view_data['name_lock'] = true;
}

// Now all the analysts can edit any source
/*
if( $USER->get('level')<=2 && $my_contact->get('fk_created_by_user') != $USER->get('id') )
  $view_data['edit_lock'] = true;
*/

$view_data['form'] = &$my_contact->form;

$view_data['id'] = $my_contact->get( 'id' );

//-------------------------------------------------------------------------------------------
// Contacts Page
//-------------------------------------------------------------------------------------------
if( $view_data['name_lock'] )
  $PAGE->title      = $LANG['source_hidden'];
else
  $PAGE->title      = $my_contact->get( 'first_name' ).' '.$my_contact->get( 'last_name' );

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

load_view( 'contacts/view_contact', $view_data );

//------------------------------------------------------------
?>
 </div> 

<script type="text/javascript">
function delete_int( id )
{
  Dom.get( 'f_int_del-int_id' ).value = id;
  show( 'int_del_popup' );
}
function delete_act( id )
{
  Dom.get( 'f_act_del-act_id' ).value = id;
  show( 'act_del_popup' );
}
</script> 

 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?
if( $USER->get('level') >= 5  )
{
  load_view( 'popups/int_delete' );
  load_view( 'popups/activity_delete' );
}

load_view( 'popups/contact_org_delete' );
load_view( 'popups/contact_prj_delete' );
load_view( 'popups/email_interview' );

//===========================================================================================
load_view( 'pagetail' );
  
?>
