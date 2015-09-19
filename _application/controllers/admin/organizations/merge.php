<?
$SESS->require_login( $USER, PATH_SELF, 5 );

$form_data = $_POST;

if( $form_data['submit_form'] )
{
  if( !$form_data['search1_val'] || !$form_data['search2_val'] )
    $SESS->set_message( 'failure', 'Organization not found.' );
  elseif( $form_data['search1_val'] == $form_data['search2_val'] )
    $SESS->set_message( 'failure', 'Cannot choose same organization for both sides of merge.' );
  else
  {
    $org_1 = $form_data['search1_val'];
    $org_2 = $form_data['search2_val'];

    // contact_orgs
    $sql = 'UPDATE contact_orgs SET fk_organization_id='.$org_2.' WHERE fk_organization_id='.$org_1;
    if( !mysql_query( $sql ) )
    {
      $SESS->set_message( 'failure', 'There was a problem merging the organizations.' );
      $problem = true;
    }

    // projects
    $sql = 'UPDATE projects SET fk_client_id='.$org_2.' WHERE fk_client_id='.$org_1;
    if( !mysql_query( $sql ) )
    {
      $SESS->set_message( 'failure', 'There was a problem merging the organizations.' );
      $problem = true;
    }

    // organizations
    if( !$problem )
    {
      $sql = 'DELETE FROM organizations WHERE pk_id='.$org_1;
      mysql_query( $sql );

      $SESS->redirect_msg( 'success', 'Organizations merged successfully.' );
    }
  }
}

$PAGE->title = "Merge Organizations";
filter_page_vars( 0 );


$PAGE->add_script( 'xmlhttpreq.js' );
$PAGE->add_script( 'forms/contacts.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );

load_view( 'pagehead' );
// -----------------------------------------------------------------------------------------

?>
<div class="page_content">
  <div class="page_content_full">
<? //------------------------------------------------------------ ?>

   <h1><img src="img/icons/big/building.png" />Merge Organizations</h1>

<?

$form = new CORE_Form( 'f_search' );


$form->add_input( 'search1_val', '', NULL, 'hidden' );
$form->add_input( 'search1', 'Former Org', NULL, 'search' );

echo '<form id="f_search" name="f_search" method="post" action="admin/organizations/merge">
<input type="hidden" name="submit_form" value="1">';
echo '<br/>Select Organization to merge records from (will no longer exist after merge)'.SP_DIV;
echo $form->print_item( 'search1' );
echo $form->print_item( 'search1_val' );
echo SP_DIV.'<br/>';

$form->add_input( 'search2', 'New Org', NULL, 'search' );
$form->add_input( 'search2_val', '', NULL, 'hidden' );

echo 'Select Organization to merge records into'.SP_DIV;
echo $form->print_item( 'search2' );
echo $form->print_item( 'search2_val' );
echo SP_DIV.'<br/><br/><b>Warning:</b> This cannot be undone.';

?>
   <div class="buttons">
    <a href="javascript:Dom.get('f_search').submit()" class="button_red"><span>Save &raquo;</span></a>
    <a href="home" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
</form>

<? //------------------------------------------------------------ ?>
  </div>
</div> <!-- end of page_content -->


<script type="text/javascript">
var org_search1 = new Xhr_search( 'org_search1', 'f_search-search1', 'organizations' );

org_search1._write_back = 'search_update_value("org_search1", value, "org", 1)';
org_search1._show = 'org_show("org_search1", resp, "org", 1)';
addEventHandler( 'onkeyup', org_search1.id, 'search_clear_value("org_search1", "org", 1)' );

addLoadEvent( "if( Dom.get(org_search1.id).value ) Dom.addClass( org_search1.id, 'org' )" );



var org_search2 = new Xhr_search( 'org_search2', 'f_search-search2', 'organizations' );

org_search2._write_back = 'search_update_value("org_search2", value, "org", 1)';
org_search2._show = 'org_show("org_search2", resp, "org", 1)';
addEventHandler( 'onkeyup', org_search2.id, 'search_clear_value("org_search2", "org", 1)' );

addLoadEvent( "if( Dom.get(org_search2.id).value ) Dom.addClass( org_search2.id, 'org' )" );

/*
var poc_search = new Xhr_search( 'poc_search', 'f_project-poc_search', 'contacts' );

poc_search._write_back = 'poc_update_value("poc_search.resp_obj.values", value)';
poc_search._show = 'org_show("poc_search", resp, "contact", 0, 1)';
addEventHandler( 'onkeyup', poc_search.id, 'poc_clear_value()' );

addLoadEvent( "if( Dom.get(poc_search.id).value ) Dom.addClass( poc_search.id, 'contact' )" );
*/
</script>


<?

load_view( 'pagetail' );

?>