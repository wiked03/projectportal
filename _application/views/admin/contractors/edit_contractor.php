<?

global $LANG, $SESS;


?>

 <div id="user_form">
  <h1>
    <img src="img/icons/big/user_<?=strtolower($add_edit)?>.png" />
    <?=$add_edit?> Contractor
  </h1>

  <form method="post" name="f_user" id="f_user" action="admin/contractors/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

echo $form->print_item( 'name', 1, 'required' );

echo $form->print_item( 'email', 1, 'required' );

echo $form->print_item( 'active' );
?>
</div>
<div class="full_width">

<?

//$form->set_tag( 'notes', 'rows', 6, 1 );
//echo $form->print_item( 'notes', 1, 'full' );


?>
</div>

<script type="text/javascript">
var user_form = new Validate( 'user_form', 'f_user', <?=$form->get_validation_json( )?> );
user_form.back_link = "<?=$SESS->get_redirect()?>";
</script>

   <div class="buttons">
    <a href="javascript:user_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:user_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
