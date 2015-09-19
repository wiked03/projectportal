<?

global $LANG, $SESS;

$my_view = new View();

if( $search_string )
  echo '<div class="search_text" id="contact_search_text">'.$search_string.'
<a onclick="hide(\'contact_search_text\');show(\'contact_search_form\');" class="hilight img search_add">Search</a>
 </div>';

else
  echo '<div id="contact_search_text"><a onclick="hide(\'contact_search_text\');show(\'contact_search_form\');" class="hilight img search_add">Show Search form</a></div>';
?>

 

 <div class="search_form" id="contact_search_form">
  <form method="post" name="f_contact" id="f_contact" action="<?=$path?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

$form->add_input( 'source_id', 'ID' );
echo $form->print_item( 'source_id' );

echo $form->print_item( 'first_name' );

echo $form->print_item( 'last_name' );

?>

   <div class="form_item">
    <label for="f_contact-org_search">Organization:</label>
<div class="xhr_search_container">
    <input autocomplete="off" type="text" name="org_search" id="f_contact-org_search" <?=$form->print_tag( 'org_search', 1 )?> />
</div>
<?=SP_DIV?>
   </div>

<?

$form->add_input( 'city', 'City' );
echo $form->print_item( 'city' );

$form->add_input( 'zipcode', 'Zipcode' );
echo $form->print_item( 'zipcode' );

$form->add_select( 'state', $LANG['states_short'], 'State / Country', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_state' ) );
echo $form->print_item( 'state', 1 );

$form->add_select( 'country', $LANG['countries'], '', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_country' ) );
echo $form->print_item( 'country', 1 );


$form->set_tag( 'notes', 'rows', '2', true );
$form->set_label( 'notes', 'Background / Notes' );
echo $form->print_item( 'notes' );

?>

</div>
<div class="right_side">
<?
echo $form->print_item( 'title' );

$form->set_label( 'email1', 'Email' );
echo $form->print_item( 'email1' );

$form->set_label( 'phone1', 'Phone' );
echo $form->print_item( 'phone1' );


//$form->add_select( 'type1', $LANG['source_types_full'], 'Source Type', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_type' ) );
//echo $form->print_item( 'type1', 1, 'type' );

$form->update_select( 'is_source', NULL, array(2=>'- any -') );
echo $form->print_item( 'is_source' );

$form->update_select( 'recontact', NULL, array(2=>'- any -') );
echo $form->print_item( 'recontact' );

$form->add_select( 'specialty1', $LANG['specialties'], 'Therapeutic Area', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_specialty' ) );
echo $form->print_item( 'specialty1' );

$form->add_select( 'degree1', $LANG['degrees'], 'Degree', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_degree' ) );
echo $form->print_item( 'degree1' );

$projects = $my_view->get_list( 'all_projects', F_HTM );
$form->add_select( 'project_list', $projects, 'Projects', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_projects' ) );

echo $form->print_item( 'project_list' );

$conferences = $my_view->get_list( 'all_conferences', F_HTM );
$form->add_select( 'conference_list', $conferences, 'Conferences', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_conferences' ) );

echo $form->print_item( 'conference_list' );

?>
</div>


<?
$col_names = $list->get_column_titles( );
$overrides = $list->get_overrides( );

$form->add_select( 'col', $col_names, '', array( 'multi'=>1, 'me'=>'sel_columns', 'override'=>$overrides ) );
echo $form->print_select_popup( 'col' );
?>

<div id="f_source-remember-checkbox">
  	<input type="checkbox" class="checkbox" name="remember" id="f_source-remember" value="1">
     Save Column Layout
</div>

<a onclick="hide('contact_search_form');show('contact_search_text');" class="hilight img search_delete">Hide</a>


<script type="text/javascript">
var sel_specialty = new Select_multi( 'sel_specialty', 'f_contact-specialty1' );
//var sel_type = new Select_multi( 'sel_type', 'f_contact-type1' );
var sel_degree = new Select_multi( 'sel_degree', 'f_contact-degree1' );
var sel_state = new Select_multi( 'sel_state', 'f_contact-state' );
sel_state.item_text = '';
var sel_country = new Select_multi( 'sel_country', 'f_contact-country' );
sel_country.item_text = 'countries';

var sel_columns = new Select_multi( 'sel_columns', 'f_contact-col' );
sel_columns.override_output = 'Select Columns';
sel_columns.default_value = '';
sel_columns.clear_all = '';

function hideSelColumns(){	
	var elem = document.getElementById('f_contact-col_select');
	elem.style.display = 'none';
	sel_columns.clear = 0;
	sel_columns.visible = false;
}

var sel_projects = new Select_multi( 'sel_projects', 'f_contact-project_list' );
sel_projects.item_text = 'projects';

var sel_conferences = new Select_multi( 'sel_conferences', 'f_contact-conference_list' );
sel_conferences.item_text = 'conferences';

var org_search = new Xhr_search( 'org_search', 'f_contact-org_search', 'organizations' );

org_search._write_back = 'org_update_value("org_search.resp_obj.values", value)';
org_search._show = 'org_show("org_search", resp, "org", 1)';

contact_default_columns = '<?=$form->get( 'col' )?>';

<?
if( !$search_string )
{
?>
addLoadEvent( 'hide(\'contact_search_text\');show(\'contact_search_form\');' );
<?
}
?>

$(document).ready(function() {
    $('#f_contact-first_name').keydown(function(event) {
        if (event.keyCode == 13) {
        	make_contact_query();
            return false;
         }
    });
    $('#f_contact-last_name').keydown(function(event) {
        if (event.keyCode == 13) {
        	make_contact_query();
            return false;
         }
    });
});


</script>


   <div class="buttons">
    <a onclick="make_contact_query()" class="button_red"><span>Search &raquo;</span></a>
    <a onclick="reset_form('f_contact')" class="button_gray"><span>Clear</span></a>
    <?=SP_DIV?>
   </div>

  </form>
 </div>
