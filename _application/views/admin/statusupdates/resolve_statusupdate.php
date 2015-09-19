<?

global $LANG, $REGEX, $SESS;

?>

 <div id="resource_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   Resolve Status Update
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/statusupdates/resolve/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">
   <input type="hidden" name="status" value="1">

<div class="full_width">
<?

$form->set_tag( 'resolution', 'rows', 12, 1 );
echo $form->print_item( 'resolution', $edit_lock, 'full' );
?>

</div>

<script type="text/javascript">

//----------------------------------
var resource_form = new Validate( 'resource_form', 'f_exp', <?=$form->get_validation_json( )?> );
resource_form.back_link = "<?=$SESS->get_redirect()?>";
</script>

   <div class="buttons">
    <a href="javascript:resource_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:resource_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
