<?

global $LANG, $SESS;

$my_view = new View();

if( $search_string )
  echo '<div class="search_text" id="interview_search_text">
<a onclick="hide(\'interview_search_text\');show(\'interview_search_form\');" class="hilight img search_add">Search</a>'.$search_string.'
 </div>';

else
  echo '<div id="interview_search_text"><a onclick="hide(\'interview_search_text\');show(\'interview_search_form\');" class="hilight img search_add">Show Search form</a></div>';
?>

 

 <div class="search_form" id="interview_search_form">
  <form method="post" name="f_int" id="f_int" action="<?=$path?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

$form->add_input( 'source_id', 'ID' );
echo $form->print_item( 'source_id' );


$form->add_input( 'first_name', 'First Name', array('maxlength'=>30) );
$form->add_input( 'last_name', 'Last Name', array('maxlength'=>30) );
echo $form->print_item( 'first_name' );

echo $form->print_item( 'last_name' );

echo $form->print_item( 'org_search' );

$form->add_input( 'city', 'City' );
echo $form->print_item( 'city' );

$form->add_select( 'state', $LANG['states_short'], 'State / Country', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_state' ) );
echo $form->print_item( 'state', 1 );

$form->add_select( 'country', $LANG['countries'], '', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_country' ) );
echo $form->print_item( 'country', 1 );

$form->add_input( 'title', 'Title' );
echo $form->print_item( 'title' );

$form->set_tag( 'int_notes', 'rows', '2', true );
$form->set_label( 'int_notes', 'Relevant Interview Background / PRS Notes' );
echo $form->print_item( 'int_notes' );

?>

</div>
<div class="right_side">

<?
$range_types = array( '0'=>'- any -', '1'=>'Custom', 'lw'=>'Last Week', 'lm'=>'Last Month', 'cm'=>'Current Month', 'ly'=>'Last Year', 'ytd'=>'Year-to-Date' );
$form->add_select( 'select_date', $range_types, 'Date Range' );

echo $form->print_item( 'select_date' );



$form->add_input( 'start', 'From', NULL, 'date'  );
$form->add_input( 'end', 'To', NULL, 'date'  );

echo $form->print_item( 'start', 1, 'date' );

echo $form->print_item( 'end', 1, 'date end_date' );


$projects = $my_view->get_list( 'all_projects', F_HTM );
$form->add_select( 'project_list', $projects, 'Projects', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_projects' ) );

echo $form->print_item( 'project_list' );

$conferences = $my_view->get_list( 'all_conferences', F_HTM );
$form->add_select( 'conference_list', $conferences, 'Conferences', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_conferences' ) );

echo $form->print_item( 'conference_list' );


$users = $my_view->get_list( 'all_users', F_HTM );

$form->add_select( 'analyst_list', $users, 'Primary Research Specialists', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_analysts' ) );
echo $form->print_item( 'analyst_list' );

//$form->add_select( 'approaches', $LANG['approach'], 'Approach', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_approach' ) );
//echo $form->print_item( 'approaches' );

$form->add_select( 'type1', $LANG['source_types_full'], 'Source Type', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_type' ) );
echo $form->print_item( 'type1', 1, 'type' );

$form->add_select( 'specialty1', $LANG['specialties'], 'Therapeutic Area', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_specialty' ) );
echo $form->print_item( 'specialty1' );

$form->set_tag( 'confidential', 'rows', '2', true );
$form->set_label( 'confidential', 'Interview Text' );
echo $form->print_item( 'confidential' );

$form->set( 'is_activity1', 0 );
$form->add_select( 'is_activity1', $LANG['yes'], 'Include Activities' );
echo $form->print_item( 'is_activity1' );

?>


</div>


<?
$col_names = $list->get_column_titles( );
$overrides = $list->get_overrides( );

$form->add_select( 'col', $col_names, '', array( 'multi'=>1, 'me'=>'sel_columns', 'override'=>$overrides ) );
echo $form->print_select_popup( 'col' );
?>

<div id="f_int-remember-checkbox">
  	<input type="checkbox" class="checkbox" name="remember" id="f_int-remember" value="1">
     Save Column Layout
</div>

<a onclick="hide('interview_search_form');show('interview_search_text');" class="hilight img search_delete">Hide</a>


<script type="text/javascript">
var sel_type = new Select_multi( 'sel_type', 'f_int-type1' );
var sel_specialty = new Select_multi( 'sel_specialty', 'f_int-specialty1' );
//var sel_approach = new Select_multi( 'sel_approach', 'f_int-approaches' );
var sel_analysts = new Select_multi( 'sel_analysts', 'f_int-analyst_list' );
sel_analysts.item_text = 'analysts';

var sel_projects = new Select_multi( 'sel_projects', 'f_int-project_list' );
sel_projects.item_text = 'projects';

var sel_conferences = new Select_multi( 'sel_conferences', 'f_int-conference_list' );
sel_conferences.item_text = 'conferences';

var sel_columns = new Select_multi( 'sel_columns', 'f_int-col' );
sel_columns.override_output = 'Select Columns';
sel_columns.default_value = '';
sel_columns.clear_all = '';

function hideSelColumns(){	
	var elem = document.getElementById('f_int-col_select');
	elem.style.display = 'none';
	sel_columns.clear = 0;
	sel_columns.visible = false;
}

var sel_state = new Select_multi( 'sel_state', 'f_int-state' );
sel_state.item_text = '';
var sel_country = new Select_multi( 'sel_country', 'f_int-country' );
sel_country.item_text = 'countries';

var cal_start = new Calendar( 'cal_start', 'f_int-start', 1 );
var cal_end   = new Calendar( 'cal_end', 'f_int-end', 1 );

cal_start.link_date( 'lt', 'cal_end' );
cal_end.link_date( 'gt', 'cal_start' );
addEventHandler( 'onchange', 'f_int-end', "if(Dom.get('f_int-end').value)Dom.get('f_int-select_date').selectedIndex=1;" );
addEventHandler( 'onchange', 'f_int-start', "if(Dom.get('f_int-start').value)Dom.get('f_int-select_date').selectedIndex=1;" );
addEventHandler( 'onchange', 'f_int-select_date', "select_date_change( this );" );

function select_date_change( obj )
{

  Dom.get('f_int-start').value = makeDate( obj.value );
  Dom.get('f_int-end').value   = makeDate( obj.value , 1 );

//  cal_end.update_range();
//  cal_start.update_range();
}


var org_search = new Xhr_search( 'org_search', 'f_int-org_search', 'organizations' );

org_search._write_back = 'org_update_value("org_search.resp_obj.values", value)';
org_search._show = 'org_show("org_search", resp, "org", 1)';

interview_default_columns = '<?=$form->get( 'col' )?>';

<?
if( !$search_string )
{
?>
addLoadEvent( 'hide(\'interview_search_text\');show(\'interview_search_form\');' );
<?
}
?>

$(document).ready(function() {
    $('#f_int-first_name').keydown(function(event) {
        if (event.keyCode == 13) {
        	make_interview_query();
            return false;
         }
    });
    $('#f_int-last_name').keydown(function(event) {
        if (event.keyCode == 13) {
        	make_interview_query();
            return false;
         }
    });
});

</script>


   <div class="buttons">
    <a onclick="make_interview_query()" class="button_red"><span>Search &raquo;</span></a>
    <a onclick="reset_form('f_int')" class="button_gray"><span>Clear</span></a>
    <?=SP_DIV?>
   </div>

  </form>
 </div>
