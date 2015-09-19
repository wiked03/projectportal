<?

global $LANG, $REGEX, $SESS;

?>

 <div id="expense_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Expense
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/expenses/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<h3 class="underline">Contractor Information</h3>

<div class="left_side">
<?

if (strtolower($add_edit)=="new"){
  $my_view = new View( );
  $contractors = $my_view->get_list( 'project_contractors', F_HTM, str_replace('c-', '', $id));
  if($contractors){
      $form->add_select( 'fk_contractor_id', $contractors, 'Contractor' );
  }
} else {
  $my_view = new View( );
  $contractors = $my_view->get_list( 'project_contractors', F_HTM, $my_expense->fk_contractor_id);
  if($contractors){
      $form->add_select( 'fk_contractor_id', $contractors, 'Contractor' );
  }
}

if( !$edit_lock ) {
  
  echo $form->print_item( 'other_expense' );
  echo $form->print_item( 'fk_contractor_id' );
  
} else {
  
  echo $form->print_item( 'other_expense' );
  echo $form->print_item( 'fk_contractor_id' );
  
}
?>
</div>

<h3 class="underline">Expense Information</h3>

<div class="left_side">
<?

if( $edit_lock )
  $form->add_validation( 'int_date', V_REQUIRED );

if( $edit_lock )
  $form->add_validation( 'int_amount', V_REQUIRED );

echo $form->print_item( 'int_date', $edit_lock, 'date' );

echo $form->print_item( 'int_amount', $edit_lock, 'text' );


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

$form->set_tag( 'notes', 'rows', 12, 1 );
echo $form->print_item( 'notes', $edit_lock, 'full' );
?>

</div>

<script type="text/javascript">
<?
if( $edit_lock )
{
?>
var cal_int_date = new Calendar( 'cal_int_date', 'f_exp-int_date', 1 );
<?
}
?>

//----------------------------------
var expense_form = new Validate( 'expense_form', 'f_exp', <?=$form->get_validation_json( )?> );
expense_form.back_link = "<?=$SESS->get_redirect()?>";
</script>




   <div class="buttons">
    <a href="javascript:expense_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:expense_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
