<?

global $LANG, $REGEX, $SESS;

?>

 <div id="confproj_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Conference
  </h1>

  <form method="post" name="f_exp" id="f_exp" action="admin/confprojs/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<h3 class="underline">Conference Information</h3>

<div class="left_side">
<?

if (strtolower($add_edit)=="new"){
  $my_view = new View( );
  $conferences = $my_view->get_list( 'all_active_conferences', F_HTM );
  if($conferences){
      $form->add_select( 'fk_conference_id', $conferences, 'Conference' );
  }
} else {
  $my_view = new View( );
  $conferences = $my_view->get_list( 'all_active_conferences', F_HTM, $my_confproj->fk_conference_id);
  if($conferences){
      $form->add_select( 'fk_conference_id', $conferences, 'Conference' );
  }
}

if( !$edit_lock ) {
  echo $form->print_item( 'fk_conference_id' );
} else {
  echo $form->print_item( 'fk_conference_id' );
}

if( $edit_lock )
  $form->add_validation( 'int_amount', V_REQUIRED );

echo $form->print_item( 'int_amount', $edit_lock, 'text' );

echo $form->print_item( 'num_days' );

echo $form->print_item( 'attendees' );

$name = $form->get( 'project', F_HTM );
$disp = '<span class="project">'.$name.'</span>';
echo $form->print_item( 'project', 0, NULL, $disp );
?>

</div>

<script type="text/javascript">

var sel_users = new Select_multi( 'sel_users', 'f_exp-attendees' );
sel_users.clear_all = '';
sel_users.default_text = 'None Selected';
sel_users.item_text = 'attendees';

//----------------------------------
var confproj_form = new Validate( 'confproj_form', 'f_exp', <?=$form->get_validation_json( )?> );
confproj_form.back_link = "<?=$SESS->get_redirect()?>";
</script>




   <div class="buttons">
    <a href="javascript:confproj_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:confproj_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
