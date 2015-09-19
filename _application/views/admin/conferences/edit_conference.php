<?

global $LANG, $SESS;


?>

 <div id="conference_form">
  <h1>
    <img src="img/icons/big/user_<?=strtolower($add_edit)?>.png" />
    <?=$add_edit?> Conference
  </h1>

  <form method="post" name="f_conference" id="f_conference" action="admin/conferences/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

echo $form->print_item( 'name', 1, 'required' );

if( $edit_lock )
	$form->add_validation( 'conference_date', V_REQUIRED );

echo $form->print_item( 'conference_date', $edit_lock, 'date' );

if( $edit_lock )
	$form->add_validation( 'conference_end_date', V_REQUIRED );

echo $form->print_item( 'conference_end_date', $edit_lock, 'date' );

echo $form->print_item( 'location' );

//echo $form->print_item( 'attendees' );

echo $form->print_item( 'active' );
?>
</div>
<div class="full_width">

<?

?>
</div>

<script type="text/javascript">


<?
if( $edit_lock )
{
?>
var cal_conference_date = new Calendar( 'cal_conference_date', 'f_conference-conference_date', 1 );
var cal_conference_end_date = new Calendar( 'cal_conference_end_date', 'f_conference-conference_end_date', 1 );
<?
}
?>

var sel_users = new Select_multi( 'sel_users', 'f_conference-attendees' );
sel_users.clear_all = '';
sel_users.default_text = 'None Selected';
sel_users.item_text = 'attendees';

var conference_form = new Validate( 'conference_form', 'f_conference', <?=$form->get_validation_json( )?> );
conference_form.back_link = "<?=$SESS->get_redirect()?>";
</script>

   <div class="buttons">
    <a href="javascript:conference_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:conference_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
