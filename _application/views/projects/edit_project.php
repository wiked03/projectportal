<?

global $LANG, $REGEX, $SESS;

?>

 <div id="project_form">
  <h1>
   <img src="img/icons/big/report_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Project
  </h1>

  <form method="post" name="f_project" id="f_project" action="projects/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?


echo '<label for="f_project-id_prefix"><b>Project ID:</b></label><div style="font-size: 13px; margin:5px 15px 3px 0;">' . $id_prefix . '</div>';

echo $form->print_item( 'name', 1, 'required' );

echo $form->print_item( 'prefix' );

echo $form->print_item( 'description' );

echo $form->print_item( 'org_search', 1, 'required' );

//echo $form->print_item( 'org_search_target', 1, 'required' );

echo $form->print_item( 'poc' );

echo $form->print_item( 'is_life_science' );

echo $form->print_item( 'specialty' );

echo $form->print_item( 'industry' );

?>

</div>
<div class="right_side">

<?

echo $form->print_item( 'bd_poc' );

echo $form->print_item( 'fk_dir_id' );

echo $form->print_item( 'fk_pm_id' );

echo $form->print_item( 'analysts' );

echo $form->print_item( 'collectors' );

echo $form->print_item( 'contractors' );

//echo $form->print_item( 'conferences' );

echo $form->print_item( 'value' );

echo $form->print_item( 'hourly_rate' );

echo $form->print_item( 'months' );

?>
</div>

<div class="full_width">

<?
echo $form->print_item( 'start', 1, 'date' );

echo $form->print_item( 'end', 1, 'date end_date' );

echo $form->print_item( 'is_active' );

echo $form->print_item( 'notes', 1, 'full' );

?>

</div>


<script type="text/javascript">

var sel_specialty = new Select_multi( 'sel_specialty', 'f_project-specialty' );
sel_specialty.clear_all = '1000';
sel_specialty.default_value = 1000;
sel_specialty.default_text = 'n/a';
sel_specialty.item_text = '';

var sel_industry = new Select_multi( 'sel_industry', 'f_project-industry' );
sel_industry.clear_all = '1000';
sel_industry.default_value = 1000;
sel_industry.default_text = 'n/a';
sel_industry.item_text = '';

var sel_users = new Select_multi( 'sel_users', 'f_project-analysts' );
sel_users.clear_all = '';
sel_users.default_text = 'None Selected';
sel_users.item_text = 'analysts';

/*
var sel_conferences = new Select_multi( 'sel_conferences', 'f_project-conferences' );
sel_conferences.clear_all = '';
sel_conferences.default_text = 'None Selected';
sel_conferences.item_text = 'conferences';
*/

var sel_collectors = new Select_multi( 'sel_collectors', 'f_project-collectors' );
sel_collectors.clear_all = '';
sel_collectors.default_text = 'None Selected';
sel_collectors.item_text = 'collectors';

var sel_contractors = new Select_multi( 'sel_contractors', 'f_project-contractors' );
sel_contractors.clear_all = '';
sel_contractors.default_text = 'None Selected';
sel_contractors.item_text = 'contractors';

//--------------------

var org_search = new Xhr_search( 'org_search', 'f_project-org_search', 'organizations' );

org_search._write_back = 'org_update_value("org_search.resp_obj.values", value, 1, 1)';
org_search._show = 'org_show("org_search", resp, "org")';
addEventHandler( 'onkeyup', org_search.id, 'org_clear_value()' );

addLoadEvent( "if( Dom.get(org_search.id).value ) Dom.addClass( org_search.id, 'org' )" );

//--------------------
/*
var org_search_target = new Xhr_search( 'org_search_target', 'f_project-org_search_target', 'organizations' );

org_search_target._write_back = 'org_target_update_value("org_search_target.resp_obj.values", value, 1, 1)';
org_search_target._show = 'org_show("org_search_target", resp, "org")';
addEventHandler( 'onkeyup', org_search_target.id, 'search_clear_value("org_search_target", "org", 1)' );

addLoadEvent( "if( Dom.get(org_search_target.id).value ) Dom.addClass( org_search_target.id, 'org' )" );
*/
//--------------------

var cal_start = new Calendar( 'cal_start', 'f_project-start', 1 );
var cal_end   = new Calendar( 'cal_end', 'f_project-end', 1 );

cal_start.link_date( 'lt', 'cal_end' );
cal_end.link_date( 'gt', 'cal_start' );

//----------------------------------
var project_form = new Validate( 'project_form', 'f_project', <?=$form->get_validation_json( )?> );
project_form.back_link = "<?=$SESS->get_redirect()?>";

addEventHandler( 'onchange', 'f_project-is_life_science', 'make_anonymous()' )
addLoadEvent( 'make_anonymous()' )

function make_anonymous( )
{
  // anonymous source
  if( Dom.get('f_project-is_life_science').value == 1 ) // life science
  {
    sel_specialty.enabled = true;
    sel_industry.enabled = false;
    $("#f_project-specialty").fadeTo(100, 1);
    $("#f_project-industry").fadeTo(100, 0.2);

  }
  else  // non life science
  {
    sel_specialty.enabled = false;
    sel_industry.enabled = true;
    $("#f_project-specialty").fadeTo(100, 0.2);
    $("#f_project-industry").fadeTo(100, 1);
  }
}


</script>



   <div class="buttons">
    <a href="javascript:project_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:project_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
