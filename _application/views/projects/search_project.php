<?

global $LANG, $SESS;

$my_view = new View();

if( $search_string )
  echo '<div class="search_text" id="project_search_text">'.$search_string.'
<a onclick="hide(\'project_search_text\');show(\'project_search_form\');" class="hilight img search_add">Search</a>
 </div>';

else
  echo '<div id="project_search_text"><a onclick="hide(\'project_search_text\');show(\'project_search_form\');" class="hilight img search_add">Show Search form</a></div>';
?>

 

 <div class="search_form" id="project_search_form">
  <form method="post" name="f_project" id="f_project" action="<?=$path?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

$form->add_input( 'project_id', 'ID' );
echo $form->print_item( 'project_id' );

echo $form->print_item( 'name' );

echo $form->print_item( 'prefix' );

echo $form->print_item( 'description' );
?>

   <div class="form_item">
    <label for="f_project-org_search">Client:</label>
	<div class="xhr_search_container">
	    <input autocomplete="off" type="text" name="org_search" id="f_project-org_search" <?=$form->print_tag( 'org_search', 1 )?> />
	</div>
	<?=SP_DIV?>
   </div>

<?

//$form->set( 'poc_search_val', $form->get( 'fk_poc_id' ) );
//echo $form->print_item( 'poc_search_val' );
echo $form->print_item( 'poc' );

$form->update_select( 'is_life_science', NULL, array(2=>'Any') );
$form->set( 'is_life_science', 2 );
echo $form->print_item( 'is_life_science' );

$form->add_select( 'specialty1', $LANG['specialties'], 'Therapeutic Area', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_specialty' ) );
echo $form->print_item( 'specialty1' );

$form->add_select( 'industry', $LANG['industries'], 'Industry', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_industry' ) );
echo $form->print_item( 'industry' );

?>
</div>
<div class="right_side">
<?

echo $form->print_item( 'bd_poc' );

echo $form->print_item( 'directors' );

echo $form->print_item( 'managers' );

echo $form->print_item( 'analysts' );

echo $form->print_item( 'collectors' );

echo $form->print_item( 'contractors' );

echo $form->print_item( 'conferences' );

echo $form->print_item( 'notes' );
?>
</div>

<div class="full_width">

<?
echo $form->print_item( 'start', 1, 'date' );
echo $form->print_item( 'end', 1, 'date end_date' );

echo $form->print_item( 'start_iec', 1, 'date' );
echo $form->print_item( 'end_iec', 1, 'date end_date' );

echo "<div class='form_item' style='margin-left:2cm; font-size:small;'><b>NOTE:</b> You can use Ctrl+1, Ctrl+2, Ctrl+3 and Ctrl+4 to select the dates for Q1, Q2, Q3 and Q4 respectively.</div>";

$form->update_select( 'is_active', NULL, array(1=>'Open', 0=>'Closed', 3=>'All') );
$form->set( 'is_active', 2 );
echo $form->print_item( 'is_active' );

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

<a onclick="hide('project_search_form');show('project_search_text');" class="hilight img search_delete">Hide</a>


<script type="text/javascript">

var sel_specialty = new Select_multi( 'sel_specialty', 'f_project-specialty1' );
var sel_industry = new Select_multi( 'sel_industry', 'f_project-industry' );

var sel_columns = new Select_multi( 'sel_columns', 'f_project-col' );
sel_columns.override_output = 'Select Columns';
sel_columns.default_value = '';
sel_columns.clear_all = '';

function hideSelColumns(){	
	var elem = document.getElementById('f_project-col_select');
	elem.style.display = 'none';
	sel_columns.clear = 0;
	sel_columns.visible = false;
}

var org_search = new Xhr_search( 'org_search', 'f_project-org_search', 'organizations' );

org_search._write_back = 'org_update_value("org_search.resp_obj.values", value)';
org_search._show = 'org_show("org_search", resp, "org", 1)';

project_default_columns = '<?=$form->get( 'col' )?>';

//---------------

var sel_directors = new Select_multi( 'sel_directors', 'f_project-directors' );
sel_directors.clear_all = '';

var sel_managers = new Select_multi( 'sel_managers', 'f_project-managers' );
sel_managers.clear_all = '';

var sel_users = new Select_multi( 'sel_users', 'f_project-analysts' );
sel_users.clear_all = '';

var sel_conferences = new Select_multi( 'sel_conferences', 'f_project-conferences' );
sel_conferences.clear_all = '';

var sel_collectors = new Select_multi( 'sel_collectors', 'f_project-collectors' );
sel_collectors.clear_all = '';

var sel_contractors= new Select_multi( 'sel_contractors', 'f_project-contractors' );
sel_contractors.clear_all = '';

var cal_start = new Calendar( 'cal_start', 'f_project-start', 1 );
var cal_end   = new Calendar( 'cal_end', 'f_project-end', 1 );

cal_start.link_date( 'lt', 'cal_end' );
cal_end.link_date( 'gt', 'cal_start' );

//---------------

//---------------

var cal_start_iec = new Calendar( 'cal_start_iec', 'f_project-start_iec', 1 );
var cal_end_iec   = new Calendar( 'cal_end_iec', 'f_project-end_iec', 1 );

cal_start_iec.link_date( 'lt', 'cal_end_iec' );
cal_end_iec.link_date( 'gt', 'cal_start_iec' );

//---------------

<?
if( !$search_string )
{
?>
addLoadEvent( 'hide(\'project_search_text\');show(\'project_search_form\');' );
<?
}
?>


var isCtrl = false;

$(document).keyup(function (e) {
	if(e.which == 17) isCtrl=false;
}).keydown(function (e) {
	if(e.which == 17) isCtrl=true;
	if(e.which == 49 && isCtrl == true) {
		//Q1
    var year = new Date().getFullYear() 
    $('#f_project-start_iec').val("1/1/"+year);
    $('#f_project-end_iec').val("3/31/"+year);
		return false;
	}
	if(e.which == 50 && isCtrl == true) {
		//Q2
    var year = new Date().getFullYear() 
    $('#f_project-start_iec').val("4/1/"+year);
    $('#f_project-end_iec').val("6/30/"+year);
		return false;
	}
	if(e.which == 51 && isCtrl == true) {
		//Q3
    var year = new Date().getFullYear() 
    $('#f_project-start_iec').val("7/1/"+year);
    $('#f_project-end_iec').val("9/30/"+year);
		return false;
	}
	if(e.which == 52 && isCtrl == true) {
		//Q4
    var year = new Date().getFullYear() 
    $('#f_project-start_iec').val("10/1/"+year);
    $('#f_project-end_iec').val("12/31/"+year);
		return false;
	}
});


$(document).ready(function() {
    $('#f_project-name').keydown(function(event) {
        if (event.keyCode == 13) {
        	make_project_query();
            return false;
         }
    });
});

</script>


   <div class="buttons">
    <a onclick="make_project_query()" class="button_red"><span>Search &raquo;</span></a>
    <a onclick="reset_form('f_project')" class="button_gray"><span>Clear</span></a>
    <?=SP_DIV?>
   </div>

  </form>
 </div>
