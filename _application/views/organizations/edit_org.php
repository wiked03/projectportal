<?

global $LANG, $SESS;


?>

 <div id="org_form">
  <h1>
    <img src="img/icons/big/building_<?=strtolower($add_edit)?>.png" />
    <?=$add_edit?> Organization
  </h1>

  <form method="post" name="f_org" id="f_org" action="organizations/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

echo $form->print_item( 'name' );
echo $form->print_item( 'address' );
echo $form->print_item( 'city' );
echo $form->print_item( 'state' );
echo $form->print_item( 'zipcode' );

?>
</div>
<div class="full_width">

<?

$form->set_tag( 'notes', 'rows', 6, 1 );
echo $form->print_item( 'notes', 1, 'full' );


?>
</div>

<script type="text/javascript">
var org_form = new Validate( 'org_form', 'f_org', <?=$form->get_validation_json( )?> );
org_form.back_link = "<?=$SESS->get_redirect()?>";
</script>

   <div class="buttons">
    <a href="javascript:org_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:org_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
