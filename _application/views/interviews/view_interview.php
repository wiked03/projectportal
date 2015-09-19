<?

global $LANG, $REGEX, $SESS;

$my_view = new View();

?>

 <div id="interview_form">
  <h1>
   <img src="img/icons/big/comments.png" />
   View Interview
   <a href="interviews/edit/<?=$id?>" class="h_link img edit">Edit Interview</a>
   <a href="interviews/export_word/<?=$id?>" class="h_link img export_word">Export</a>
   <a href="javascript:show('user_email_popup');" class="h_link img email">Email</a>
  </h1>

  <form name="f_int" id="f_int">

<div class="left_side">
<?

$form->add_input( 'int_id', 'ID' );
echo $form->set( 'int_id', $LANG['source_types_short'][$my_view->get( 'contact_type', $form->get('fk_contact_id') )].
                              '-'.sprintf( '%03d', $form->get('fk_contact_id') ).
                              '-'.$form->get('int_number') );
echo $form->print_item( 'int_id', 0 );

if( $form->get( 'is_source' ) == 3 )
  $name = $LANG['anonymous'];
elseif( $name_lock )
{
  $name = $LANG['source_hidden'];
}
else
{
  $name = $form->get( 'contact', F_HTM );
}

$disp = '<a href="contacts/view/'.$form->get('fk_contact_id').'" class="img_small contact">'.$name.'</a>';
if( !$name_lock )
{
if( $form->get('title') )
  $disp .= ' - <br/>'.$form->get('title');
if( $form->get('contact_org') )
  $disp .= ', <br/><a href="organizations/view/'.$form->get('contact_org').'" class="img_small org">'.$form->get('org_name').'</a>';
}

echo $form->print_item( 'contact', 0, NULL, $disp );

$disp = '<span class="user_name user">'.$form->get( 'analyst', F_HTM ).'</span>';
//$disp = '<span class="created_by user">'.$form->get( 'created_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'created' ), F_DATE_HTM );
echo $form->print_item( 'analyst', 0, NULL, $disp );

echo $form->print_item( 'int_date', 0, 'date' );

echo $form->print_item( 'method', 0 );
?>
</div>
<div class="right_side">
<?

echo $form->print_item( 'credibility', 0 );

//echo $form->print_item( 'approach', 0 );


if( $form->get('projects') )
{
  $vals = explode( '.', $form->get('projects') );
  foreach( $vals as $val )
    $values[] = '<a href="projects/view/'.$val.'" class="img_small proj">'.$my_view->get( 'project', $val, F_HTM ).'</a>';
  $projects = implode( ', ', $values );
}
echo $form->print_item( 'projects', 0, NULL, $projects );

if( $form->get('conferences') )
{
	$vals = explode( '.', $form->get('conferences') );
	foreach( $vals as $val )
		$values2[] = $my_view->get( 'conference', $val, F_HTM );
	$conferences = implode( ', ', $values2 );
}
echo $form->print_item( 'conferences', 0, NULL, $conferences );

echo $form->print_item( 'rate', 0 );

echo $form->print_item( 'paid', 0 );
?>

</div>

<div class="full_width">
<?

if( $form->get( 'int_background' ) != '' ){
	?>
<div class="form_item full" id="f_int-confidential_div">
<label>Relevant Interview Background:</label>
<span class="value" rows="4">
	<div class="reset">
		<? echo $form->get('int_background', F_PHP);?>
	</div>
</span>
<?
}

if( $form->get( 'confidential' ) != '' ){
?>
<div class="form_item full" id="f_int-confidential_div">
<label>Interview and Key Takeaways:</label>
<span class="value" rows="4">
	<div class="reset">
		<? echo $form->get('confidential', F_PHP);?>
	</div>
</span>
<?
}

/*
if( $form->get( 'source_comments' ) != '' )
  echo $form->print_item( 'source_comments', 0, 'full', CORE_make_links(nl2br($form->get('source_comments', F_HTM))) );

if( $form->get( 'analyst_comments' ) != '' )
  echo $form->print_item( 'analyst_comments', 0, 'full', CORE_make_links(nl2br($form->get('analyst_comments', F_HTM))) );
*/

if( $form->get( 'int_notes' ) != '' ){
?>
<div class="form_item full" id="f_int-confidential_div">
<label>PRS Notes:</label>
<span class="value" rows="4">
	<div class="reset">
		<? echo $form->get('int_notes', F_PHP);?>
	</div>
</span>
<?
}
?>

		</div>

  		</form>
	 </div>
  </div>
</div>
