<?

global $LANG, $REGEX, $SESS;

$my_view = new View();

?>

 <div id="interview_form">
  <h1>
   <img src="img/icons/big/phone.png" />
   View Activity
   <a href="activities/edit/<?=$id?>" class="h_link img edit">Edit Activity</a>
  </h1>

  <form name="f_int" id="f_int">

<div class="left_side">
<?

$form->add_input( 'int_id', 'ID' );
echo $form->set( 'int_id', $LANG['source_types_short'][$my_view->get( 'contact_type', $form->get('fk_contact_id') )].
                              '-'.sprintf( '%03d', $form->get('fk_contact_id') ).
                              '-'.$form->get('int_number') );
echo $form->print_item( 'int_id', 0 );

$disp = '<a href="contacts/view/'.$form->get('fk_contact_id').'" class="img_small contact">'.$form->get( 'contact', F_HTM ).'</a>';


if( $form->get('title') )
  $disp .= ' - <br/>'.$form->get('title');
if( $form->get('contact_org') )
  $disp .= ', <br/><a href="organizations/view/'.$form->get('contact_org').'" class="img_small org">'.$form->get('org_name').'</a>';

echo $form->print_item( 'contact', 0, NULL, $disp );

$disp = '<span class="user_name user">'.$form->get( 'analyst', F_HTM ).'</span>';
//$disp = '<span class="created_by user">'.$form->get( 'created_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'created' ), F_DATE_HTM );
echo $form->print_item( 'analyst', 0, NULL, $disp );

$form->labels['int_date'] = "Date";
echo $form->print_item( 'int_date', 0, 'date' );

echo $form->print_item( 'method', 0 );
?>
</div>
<div class="right_side">
<?


echo $form->print_item( 'approach', 0 );


if( $form->get('projects') )
{
  $vals = explode( '.', $form->get('projects') );
  foreach( $vals as $val )
    $values[] = '<a href="projects/view/'.$val.'" class="img_small proj">'.$my_view->get( 'project', $val, F_HTM ).'</a>';
  $projects = implode( ', ', $values );
}
echo $form->print_item( 'projects', 0, NULL, $projects );

?>

</div>

<div class="full_width">
<?

if( $form->get( 'int_notes' ) != '' )
  echo $form->print_item( 'int_notes', 0, 'full', CORE_make_links(nl2br($form->get('int_notes', F_HTM))) );
?>

</div>

  </form>
 </div>
