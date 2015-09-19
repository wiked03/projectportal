<?

global $LANG, $REGEX, $SESS;

?>

 <div id="deliverable_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Deliverable
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/deliverables/add/<?=$id?>">
   <input type="hidden" id="f_del-submit_form" name="submit_form" value="1">


<div class="left_side">
<?

echo $form->print_item( 'int_start_date', $edit_lock, 'date' );

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
  echo $form->print_item( 'clientinteraction' );
  echo $form->print_item( 'type' );
  $form->set_tag( 'notes', 'rows', 12, 1 );
  echo $form->print_item( 'notes', $edit_lock, 'full' );
?>
</div>

<script type="text/javascript">

<?
if( $edit_lock )
{
?>
var cal_int_date = new Calendar( 'cal_int_date', 'f_exp-int_start_date', 1 );
<?
}
?>

//----------------------------------
var deliverable_form = new Validate( 'deliverable_form', 'f_exp', <?=$form->get_validation_json( )?> );
deliverable_form.back_link = "<?=$SESS->get_redirect()?>";

// clean notes
Dom.get('f_exp-notes').value = '';

</script>

<br>

   <div class="buttons">
    <a href="javascript:deliverable_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:Dom.get('f_del-submit_form').value=3;deliverable_form.validate()" class="button_gray"><span>&laquo; Cancel</span></a>
    <!--  <a href="javascript:deliverable_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a> -->
    <a style="clear:right;margin-top:5px;" href="javascript:Dom.get('f_del-submit_form').value=2;deliverable_form.validate()" class="hilight img interview_add">Save and create New Deliverable</a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
