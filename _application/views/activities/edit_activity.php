<?

global $LANG, $REGEX, $SESS;

?>

 <div id="interview_form">
  <h1>
   <img src="img/icons/big/phone_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Activity
  </h1>

  <form method="post" name="f_int" id="f_int" action="activities/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

$disp = '<span class="user_name contact">'.$form->get( 'contact', F_HTM ).'</span>';
echo $form->print_item( 'contact', 0, NULL, $disp );

$disp = '<span class="user_name user">'.$form->get( 'analyst', F_HTM ).'</span>';
//$disp = '<span class="created_by user">'.$form->get( 'created_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'created' ), F_DATE_HTM );
echo $form->print_item( 'analyst', 0, NULL, $disp );


$form->labels['int_date'] = "Date";

if( $edit_lock )
  $form->add_validation( 'int_date', V_REQUIRED );

echo $form->print_item( 'int_date', $edit_lock, 'date' );

echo $form->print_item( 'method', $edit_lock );


?>
</div>
<div class="right_side">
<?

//echo $form->print_item( 'approach', $edit_lock );

// grey out projects interview already belongs to
$my_view = new View( );
$cur_projs = explode( '.', $form->get( 'projects' ) );
foreach( $cur_projs as $proj )
{
  if( !$edit_lock || !$my_view->get('project_is_active', $proj) )
    $form->settings['projects']['override'][$proj] = true;
}

echo $form->print_item( 'projects' );

?>

</div>

<div class="full_width">
<?

$form->set_tag( 'int_notes', 'rows', 6, 1 );
echo $form->print_item( 'int_notes', $edit_lock, 'full' );
?>

</div>


<script type="text/javascript">
var sel_projects = new Select_multi( 'sel_projects', 'f_int-projects' );
sel_projects.clear_all = '';
sel_projects.default_text = 'No projects selected';
sel_projects.item_text = 'projects';

<?
if( $edit_lock )
{
?>
var int_date = new Calendar( 'int_date', 'f_int-int_date', 1 );
<?
}
?>

//----------------------------------
var interview_form = new Validate( 'interview_form', 'f_int', <?=$form->get_validation_json( )?> );
interview_form.back_link = "<?=$SESS->get_redirect()?>";
</script>




   <div class="buttons">
    <a href="javascript:interview_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:interview_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
