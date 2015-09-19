<?

global $LANG, $REGEX, $SESS;

?>

 <div id="interview_form">
  <h1>
   <img src="img/icons/big/comments_<?=strtolower($add_edit)?>.png" />
   <?=$add_edit?> Interview
  </h1>

  <form method="post" name="f_int" id="f_int" action="interviews/edit/<?=$id?>">
   <input type="hidden" name="submit_form" value="1">

<h3 class="underline">Contact Information</h3>

<div class="left_side">
<?
if( !$edit_lock ) {
  if( $name_lock ){
    $name = $LANG['source_hidden'];
  } else {
    $name = $form->get( 'contact', F_HTM );
  }
  
  $disp = '<span class="user_name contact">'.$name.'</span>';
  echo $form->print_item( 'contact', 0, NULL, $disp );
  
} else {
  $name = $form->get( 'contact', F_HTM );

  $disp = '<span class="user_name contact">'.$name.'</span>';
  echo $form->print_item( 'contact', 0, NULL, $disp );
  
  echo $form->print_item( 'salutation', 1 );

  echo $form->print_item( 'first_name', 1, 'required' );

  echo $form->print_item( 'last_name', 1, 'required' );

  echo $form->print_item( 'degree' );

  echo $form->print_item( 'title' );  
}
?>
</div>

<h3 class="underline">Interview Information</h3>

<div class="left_side">
<?

$disp = '<span class="user_name user">'.$form->get( 'analyst', F_HTM ).'</span>';
//$disp = '<span class="created_by user">'.$form->get( 'created_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'created' ), F_DATE_HTM );
echo $form->print_item( 'analyst', 0, NULL, $disp );



if( $edit_lock )
  $form->add_validation( 'int_date', V_REQUIRED );

echo $form->print_item( 'int_date', $edit_lock, 'date' );

echo $form->print_item( 'method', $edit_lock );


?>
</div>
<div class="right_side" style="margin-bottom: 10px";>
<?

echo $form->print_item( 'credibility', $edit_lock );

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


// grey out conferences interview already belongs to
$my_view = new View( );
$cur_conferences = explode( '.', $form->get( 'conferences' ) );
foreach( $cur_conferences as $proj )
{
	if( !$edit_lock || !$my_view->get('conference_is_active', $proj) )
		$form->settings['conferences']['override'][$proj] = true;
}

echo $form->print_item( 'conferences' );


echo $form->print_item( 'rate', $edit_lock );

echo $form->print_item( 'paid', $edit_lock );
?>

</div>



<div class="full_width" style='margin-top: 30px;'>
<?

$form->set_tag( 'int_background', 'rows', 9, 1 );
echo $form->print_item( 'int_background', $edit_lock, 'full' );

$form->set_tag( 'confidential', 'rows', 18, 1 ); // interview 
echo $form->print_item( 'confidential', $edit_lock, 'full' );

/*
$form->set_tag( 'source_comments', 'rows', 12, 1 );
echo $form->print_item( 'source_comments', $edit_lock, 'full' );

$form->set_tag( 'analyst_comments', 'rows', 8, 1 );
echo $form->print_item( 'analyst_comments', $edit_lock, 'full' );
*/

$form->set_tag( 'int_notes', 'rows', 9, 1 );
echo $form->print_item( 'int_notes', $edit_lock, 'full' );
?>

</div>


<script type="text/javascript">
var sel_projects = new Select_multi( 'sel_projects', 'f_int-projects' );
sel_projects.clear_all = '';
sel_projects.default_text = 'No projects selected';
sel_projects.item_text = 'projects';

var sel_conferences = new Select_multi( 'sel_conferences', 'f_int-conferences' );
sel_conferences.clear_all = '';
sel_conferences.default_text = 'No conferences selected';
sel_conferences.item_text = 'conferences';

var sel_degree = new Select_multi( 'sel_degree', 'f_int-degree' );
sel_degree.clear_all = '10';
sel_degree.default_value = 10;
sel_degree.default_text = 'n/a';
sel_degree.item_text = '';

<?
if( $edit_lock )
{
?>
var cal_int_date = new Calendar( 'cal_int_date', 'f_int-int_date', 1 );
<?
}
?>

//----------------------------------
var interview_form = new Validate( 'interview_form', 'f_int', <?=$form->get_validation_json( )?> );
interview_form.back_link = "<?=$SESS->get_redirect()?>";

</script>

<script>
	CKEDITOR.replace( 'int_background' ,{
        height: '150px'
    } );
    CKEDITOR.replace( 'confidential' ,{
        height: '300px'
    });
    CKEDITOR.replace( 'int_notes' ,{
        height: '150px'
    } );
</script>


   <div class="buttons">
    <a href="javascript:interview_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:interview_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>
