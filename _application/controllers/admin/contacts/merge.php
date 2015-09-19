<?
$SESS->require_login( $USER, PATH_SELF, 2 );

$form_data = $_POST;

if( $form_data['submit_form'] )
{
  if( !$form_data['search1_val'] || !$form_data['search2_val'] )
    $SESS->set_message( 'failure', 'Source not found.' );
  elseif( $form_data['search1_val'] == $form_data['search2_val'] )
    $SESS->set_message( 'failure', 'Cannot choose same source for both sides of merge.' );
  else
  {
    $org_1 = $form_data['search1_val'];
    $org_2 = $form_data['search2_val'];

    // contact_orgs
    $sql = "SELECT c.fk_organization_id AS org_id
            FROM contact_orgs AS c
              LEFT JOIN contact_orgs AS co ON co.fk_organization_id=c.fk_organization_id
            WHERE co.fk_contact_id=".$org_1." AND c.fk_contact_id=".$org_2;

    $result = mysql_query( $sql );

    // remove any duplicate contact_org entries
    while( $res = mysql_fetch_assoc($result) )
      mysql_query( "DELETE FROM contact_orgs WHERE fk_contact_id=".$org_1." AND fk_organization_id=".$res['org_id'] );

    $sql = 'UPDATE contact_orgs SET fk_contact_id='.$org_2.' WHERE fk_contact_id='.$org_1;
    if( !mysql_query( $sql ) )
    {
      $SESS->set_message( 'failure', 'There was a problem merging the sources.' );
      $problem = true;
    }

    // interviews
    $sql = 'UPDATE interviews SET fk_contact_id='.$org_2.' WHERE fk_contact_id='.$org_1;
    if( !mysql_query( $sql ) )
    {
      $SESS->set_message( 'failure', 'There was a problem merging the sources.' );
      $problem = true;
    }

    // projects
    $sql = 'UPDATE projects SET fk_poc_id='.$org_2.' WHERE fk_poc_id='.$org_1;
    if( !mysql_query( $sql ) )
    {
      $SESS->set_message( 'failure', 'There was a problem merging the sources.' );
      $problem = true;
    }

    // contact
    if( !$problem )
    {
      $sql = 'DELETE FROM contacts WHERE pk_id='.$org_1;
      mysql_query( $sql );

      $SESS->redirect_msg( 'success', 'Sources merged successfully.' );
    }

  }
}

$PAGE->title = "Merge Sources";
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

   <h1><img src="img/icons/big/vcard.png" />Merge Sources</h1>

<?

$form = new CORE_Form( 'f_search' );


$form->add_input( 'search1_val', '', NULL, 'hidden' );
$form->add_input( 'search1', 'Former Source', NULL, 'search' );

echo '<form id="f_search" name="f_search" method="post" action="admin/contacts/merge">
<input type="hidden" name="submit_form" value="1">';
echo '<br/>Select Source to merge records from (will no longer exist after merge)'.SP_DIV;
echo $form->print_item( 'search1' );
echo $form->print_item( 'search1_val' );
echo SP_DIV.'<br/>';

$form->add_input( 'search2', 'New Source', NULL, 'search' );
$form->add_input( 'search2_val', '', NULL, 'hidden' );

echo 'Select Source to merge records into'.SP_DIV;
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
var org_search1 = new Xhr_search( 'org_search1', 'f_search-search1', 'contacts' );

org_search1._write_back = 'search_update_value("org_search1", value, "contact", 1)';
org_search1._show = 'org_show("org_search1", resp, "contact", 1)';
addEventHandler( 'onkeyup', org_search1.id, 'search_clear_value("org_search1", "contact", 1)' );

addLoadEvent( "if( Dom.get(org_search1.id).value ) Dom.addClass( org_search1.id, 'contact' )" );



var org_search2 = new Xhr_search( 'org_search2', 'f_search-search2', 'contacts' );

org_search2._write_back = 'search_update_value("org_search2", value, "contact", 1)';
org_search2._show = 'org_show("org_search2", resp, "contact", 1)';
addEventHandler( 'onkeyup', org_search2.id, 'search_clear_value("org_search2", "contact", 1)' );

addLoadEvent( "if( Dom.get(org_search2.id).value ) Dom.addClass( org_search2.id, 'contact' )" );


</script>


<?

load_view( 'pagetail' );

?>