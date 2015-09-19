<?

global $LANG, $REGEX, $SESS;

?>

 <div id="statusupdate_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Status Update
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/statusupdates/add/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">
   <input type="hidden" name="status" value="<?=$form->data['status']?>">

<div class="left_side">
<?

echo $form->print_item( 'int_start_date', 0, NULL );
?>

</div>

<div class="right_side">
<?
  $name = $form->get( 'project', F_HTM );
  $disp = '<span class="project">'.$name.'</span>';
  echo $form->print_item( 'project', 0, NULL, $disp );
?>
</div>

<div class="full_width">
<?
  echo $form->print_item( 'concern' );
  $form->set_tag( 'notes', 'rows', 12, 1 );
  echo $form->print_item( 'notes', $edit_lock, 'full' );
?>
</div>

<script type="text/javascript">

//----------------------------------
var statusupdate_form = new Validate( 'statusupdate_form', 'f_exp', <?=$form->get_validation_json( )?> );
statusupdate_form.back_link = "<?=$SESS->get_redirect()?>";

</script>

<br>

   <div class="buttons">
    <a href="javascript:statusupdate_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:statusupdate_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
