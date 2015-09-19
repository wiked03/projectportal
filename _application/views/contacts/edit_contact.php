<?

global $LANG, $REGEX, $SESS;


$state_list = array(0=>' - ') + $LANG['states'];
$country_list = array(0=>' - ') + $LANG['countries'];

?>

 <div id="contact_form">
  <h1>
   <img src="img/icons/big/vcard_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Source
  </h1>

  <form method="post" name="f_contact" id="f_contact" action="contacts/edit/<?=$id?>">
   <input type="hidden" id="f_contact-submit_form" name="submit_form" value="1">

<div class="left_side">
<?
echo $form->print_item( 'salutation', 1 );

echo $form->print_item( 'first_name', 1, 'required' );

echo $form->print_item( 'last_name', 1, 'required' );

echo $form->print_item( 'degree' );

echo $form->print_item( 'title' );


?>
</div>
<div class="right_side">
<?


echo $form->print_item( 'specialty' );

//echo $form->print_item( 'type' );

?>
<!--<div class="form_item definitions">
<a href="javascript:show('contact_def_popup')" class="img help"></a>
<?//=SP_DIV?>
</div>-->
<?

echo $form->print_item( 'is_source' );

echo $form->print_item( 'recontact' );

echo $form->print_item( 'reliability' );

echo $form->print_item( 'language' );

echo $form->print_item( 'area' );

?>

</div>

<div class="full_width">
<?

echo $form->print_item( 'background', 1, 'full'  );

?>
</div>

<div id="f_contact_information">
<h3 class="underline">Contact Information</h3>


<div class="left_side">
<?

echo $form->print_item( 'phone1', 1, 'phone' );
echo $form->print_item( 'phone1_type', 1, 'phone_type' );

echo $form->print_item( 'phone2', 1, 'phone' );
echo $form->print_item( 'phone2_type', 1, 'phone_type' );

echo $form->print_item( 'phone3', 1, 'phone' );
echo $form->print_item( 'phone3_type', 1, 'phone_type' );

?>
</div>
<div class="right_side">
<?



?>

</div>


<div class="full_width">
<?

echo $form->print_item( 'email1', 1, 'email' );
echo $form->print_item( 'email1_type', 1, 'email_type' );

echo $form->print_item( 'email2', 1, 'email' );
echo $form->print_item( 'email2_type', 1, 'email_type' );

//echo $form->print_item( 'zipcode', 1, 'zipcode' );

echo $form->print_item( 'notes', 1, 'full' );

?>
</div>

</div>
  <h3 class="underline">Organizations</h3>

<?

echo $form->print_item( 'org_search' );

?>

<?=SP_DIV?>
<div id='f_contact-org_list'>
</div>

  <h3 class="underline">Projects</h3>
  
<?

echo $form->print_item( 'prj_search' );

?>  

<?=SP_DIV?>
<div id='f_contact-prj_list'>
</div>

<script type="text/javascript">
var sel_degree = new Select_multi( 'sel_degree', 'f_contact-degree' );
sel_degree.clear_all = '10';
sel_degree.default_value = 10;
sel_degree.default_text = 'n/a';
sel_degree.item_text = '';

var sel_specialty = new Select_multi( 'sel_specialty', 'f_contact-specialty' );
sel_specialty.clear_all = '1000';
sel_specialty.default_value = 1000;
sel_specialty.default_text = 'n/a';
sel_specialty.item_text = '';

var sel_language = new Select_multi( 'sel_language', 'f_contact-language' );
sel_language.clear_all = '1000';
sel_language.default_value = 1000;
sel_language.default_text = 'n/a';
sel_language.item_text = '';

var sel_area = new Select_multi( 'sel_area', 'f_contact-area' );
sel_area.clear_all = '1000';
sel_area.default_value = 1000;
sel_area.default_text = 'n/a';
sel_area.item_text = ''

var org_search = new Xhr_search( 'org_search', 'f_contact-org_search', 'organizations' );

org_search._write_back = 'org_write_back("org_search.resp_obj", value)';
org_search._show = 'org_show("org_search", resp, "org")';
org_search._query = 'org_query()';


var org_list = new Form_array( 'org_list', 'f_contact-org_list', 'org' );


// load the current organization list
my_org_list = <?=json_encode( $form->get( 'orgs' ) )?>;
var form_org_id = 0;

for( var i = 0; i < my_org_list.length; i++ )
{
  var o = my_org_list[i];

  org_list.add( [o.fk_organization_id, o.org_name, o.is_primary, o.is_current, o.city, o.state, o.country, o.zipcode], 1 );
}

//-------------------

var prj_search = new Xhr_search( 'prj_search', 'f_contact-prj_search', 'projects' );

prj_search._write_back = 'prj_write_back("prj_search.resp_obj", value)';
prj_search._show = 'prj_show("prj_search", resp, "prj")';
prj_search._query = 'prj_query()';


var prj_list = new Form_array( 'prj_list', 'f_contact-prj_list', 'prj' );


// load the current project list
my_prj_list = <?=json_encode( $form->get( 'prjs' ) )?>;
var form_prj_id = 0;

for( var i = 0; i < my_prj_list.length; i++ )
{
  var o = my_prj_list[i];

  prj_list.add( [o.fk_project_id, o.prj_name], 1 );
}


//----------------------------------
var contact_form = new Validate( 'contact_form', 'f_contact', <?=$form->get_validation_json( )?> );
contact_form.back_link = "<?=$SESS->get_redirect()?>";

function make_state_select_box( selected, s_id, s_name )
{
  return make_select_box( <?=json_encode($state_list)?>, selected, s_id, s_name );
}

function make_country_select_box( selected, s_id, s_name )
{
  return make_select_box( <?=json_encode($country_list)?>, selected, s_id, s_name );
}

addEventHandler( 'onchange', 'f_contact-is_source', 'make_anonymous()' )
addLoadEvent( 'make_anonymous()' )

function make_anonymous( )
{
  // anonymous source
  if( Dom.get('f_contact-is_source').value == 3 )
  {
    Dom.get('f_contact-first_name').value = "Personal";
    Dom.get('f_contact-last_name').value = "Source";
    Dom.get('f_contact-recontact').value = 0;

    Dom.get('f_contact-first_name').disabled = true;
    Dom.get('f_contact-last_name').disabled = true;
    Dom.get('f_contact-recontact').disabled = true;

    hide( 'f_contact_information' );
    $("#f_contact-prj_search_div").show();
  }
  else
  {
    if( Dom.get('f_contact-is_source').value == 4 )
    {
      Dom.get('f_contact-first_name').disabled = false;
      Dom.get('f_contact-last_name').disabled = false;
      Dom.get('f_contact-recontact').disabled = false;

      show( 'f_contact_information' );
      $("#f_contact-prj_search_div").hide();
    }
    else
    {
      Dom.get('f_contact-first_name').disabled = false;
      Dom.get('f_contact-last_name').disabled = false;
      Dom.get('f_contact-recontact').disabled = false;

      show( 'f_contact_information' );
      $("#f_contact-prj_search_div").show();
    }
  }
}

</script>

   <div class="buttons">
    <a href="javascript:contact_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:contact_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <a style="clear:right;margin-top:5px;" href="javascript:Dom.get('f_contact-submit_form').value=2;contact_form.validate()" class="hilight img interview_add">Save and create New Interview</a>
    <a style="clear:right;margin-top:5px;" href="javascript:Dom.get('f_contact-submit_form').value=3;contact_form.validate()" class="hilight img activity_add">Save and create New Activity</a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
