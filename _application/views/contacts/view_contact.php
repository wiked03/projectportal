<?

global $LANG, $REGEX, $USER;

$degrees = explode( '.', $form->get( 'degree' ) );
foreach( $degrees as $degree )
{
  if( $degree != 10 )
    $degree_str .= ', '.$LANG['degrees'][$degree];
}
?>

 <div id="contact_form">
  <h1>
    <img src="img/icons/big/vcard.png" />
<?
if( $name_lock )
{
  echo $LANG['source_hidden'];
}
else
{
?>
<?=$LANG['salutations'][$form->get( 'salutation' )].' '.$form->get( 'first_name', F_HTM ).' '.$form->get( 'last_name', F_HTM ).$degree_str?>&nbsp;
<?    
}

if( !$edit_lock )
{
?>
    <a href="contacts/edit/<?=$id?>" class="h_link img edit">Edit Source</a>
    <!--<a href="interviews/edit/c-<?=$id?>" class="h_link img interview_add">New Interview</a> -->
    <a href="activities/edit/c-<?=$id?>" class="h_link img activity_add">New Activity</a>
<?
}
?>
  </h1>

  <form name="f_contact" id="f_contact">

<div class="left_side">
<?


echo $form->set( 'source_id', $LANG['source_types_short'][$form->get( 'type' )].'-'.sprintf( '%03d', $form->get( 'pk_id' ) ) );
echo $form->print_item( 'source_id', 0 );

echo $form->print_item( 'title', 0 );

echo $form->print_item( 'degree', 0 );

echo $form->print_item( 'specialty', 0 );

echo $form->print_item( 'reliability', 0 );

echo $form->print_item( 'language', 0 );

echo $form->print_item( 'area', 0 );

?>
</div>
<div class="right_side">
<?
echo $form->print_item( 'type', 0 );
echo $form->print_item( 'is_source', 0 );

echo $form->print_item( 'recontact', 0 );

echo '<br/><br/>';

//$created_by = '<span class="user_name user"><a href="users/view/'.$form->get('fk_created_by_user').'">'.$form->get( 'created_by', F_HTM ).'</a></span>';
$created_by = '<span class="user_name user">'.$form->get( 'created_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'created' ), F_DATE_HTM );
echo $form->print_item( 'created_by', 0, NULL, $created_by );

//$changed_by = '<span class="user_name user"><a href="users/view/'.$form->get('fk_last_changed_user').'">'.$form->get( 'changed_by', F_HTM ).'</a></span>';
$changed_by = '<span class="user_name user">'.$form->get( 'changed_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'last_changed' ), F_DATE_HTM );
echo $form->print_item( 'changed_by', 0, NULL, $changed_by );

?>

</div>

<div class="full_width">
<?

if( $form->get( 'background' ) != '' )
  echo $form->print_item( 'background', 0, 'full', nl2br($form->get('background')) );

?>
</div>

<?
// hide contact info if anonymous
if( $form->get('is_source') != 3 && !$name_lock )
{
?>
<h3 class="underline">Contact Information</h3>


<div class="full_width">
<?

$form->set_label( 'phone1', $LANG['phone_types'][$form->get( 'phone1_type' )].' Phone' );
$form->set_label( 'phone2', $LANG['phone_types'][$form->get( 'phone2_type' )].' Phone' );
$form->set_label( 'phone3', $LANG['phone_types'][$form->get( 'phone3_type' )].' Phone' );

if( $form->get( 'phone1' ) != '' )
  echo $form->print_item( 'phone1', 0, 'phone' );

if( $form->get( 'phone2' ) != '' )
  echo $form->print_item( 'phone2', 0, 'phone' );

if( $form->get( 'phone3' ) != '' )
  echo $form->print_item( 'phone3', 0, 'phone' );

// if no phone at all, say something
if( $form->get( 'phone1' ) == '' && $form->get( 'phone2' ) == '' && $form->get( 'phone3' ) == '' )
{
  $form->set_label( 'phone1', 'Phone' );
  $form->set( 'phone1', '- none listed -' );

  echo $form->print_item( 'phone1', 0, 'phone' );
}


$form->set_label( 'email1', $LANG['email_types'][$form->get( 'email1_type' )].' Email' );
$form->set_label( 'email2', $LANG['email_types'][$form->get( 'email2_type' )].' Email' );

$email1 = preg_replace( $REGEX['email'], $REGEX['email_replace'][1], $form->get( 'email1', F_HTM ) );
$email2 = preg_replace( $REGEX['email'], $REGEX['email_replace'][1], $form->get( 'email2', F_HTM ) );

if( $form->get( 'email1' ) != '' )
  echo $form->print_item( 'email1', 0, 'email', $email1 );

if( $form->get( 'email2' ) != '' )
  echo $form->print_item( 'email2', 0, 'email', $email2 );

// if no email at all, say something
if( $form->get( 'email1' ) == '' && $form->get( 'email2' ) == '' )
{
  $form->set_label( 'email1', 'Email' );
  $form->set( 'email1', '- none listed -' );

  echo $form->print_item( 'email1', 0, 'email' );
}

/*
$form->set_label( 'zipcode', 'Zipcode' );
if( $form->get( 'zipcode' ) != '' ) {
  echo $form->print_item( 'zipcode', 0, 'zipcode' );
} else {
  $form->set( 'zipcode', '- none listed -' );
  echo $form->print_item( 'zipcode', 0, 'zipcode' );
}*/

if( $form->get( 'notes' ) != '' )
{
  //$form->set( 'notes', nl2br($form->get('notes')) );
  echo $form->print_item( 'notes', 0, 'full', nl2br($form->get('notes')) );
}

?>
</div>
<?
}
// end of hiding contact info
?>

  <h3 class="underline">Organizations</h3>


<div id='f_contact-view_org_list'>
</div>


<script type="text/javascript">

var org_list = new Form_array( 'org_list', 'f_contact-view_org_list', 'disporg' );



// load the current organization list
my_org_list = <?=json_encode( $form->get( 'orgs' ) )?>;
var form_org_id = 0;

for( var i = 0; i < my_org_list.length; i++ )
{
  var o = my_org_list[i];

  org_list.add( [o.fk_organization_id, o.org_name, o.is_primary, o.is_current, 
                 o.city, o.state, o.country, <?=json_encode($LANG['states'])?>, <?=json_encode($LANG['countries'])?>, o.zipcode], 1 );
}

if( !my_org_list )
  Dom.get( 'f_contact-view_org_list' ).innerHTML = '<div class="contact_org"><span class="org_name">None</span><?=SP_DIV?></div>';

</script>

  <h3 class="underline">Projects</h3>


<div id='f_contact-view_prj_list'>
</div>


<script type="text/javascript">

var prj_list = new Form_array( 'prj_list', 'f_contact-view_prj_list', 'dispprj' );



// load the current project list
my_prj_list = <?=json_encode( $form->get( 'prjs' ) )?>;
var form_prj_id = 0;

for( var i = 0; i < my_prj_list.length; i++ )
{
  var o = my_prj_list[i];

  prj_list.add( [o.fk_project_id, o.prj_name, 
                  <?=json_encode($LANG['states'])?>, <?=json_encode($LANG['countries'])?>], 1 );
}

if( !my_prj_list )
  Dom.get( 'f_contact-view_prj_list' ).innerHTML = '<div class="contact_prj"><span class="prj_name">None</span><?=SP_DIV?></div>';

</script>
                  
<script type="text/javascript">
	function show_email(interview_id){
		Dom.get( 'f_interview_id' ).value = interview_id;
		show('user_email_popup');
	}	
	function delete_org( org_id  )
	{
	  Dom.get( 'f_contact_org_del_org_id' ).value = org_id;
	  Dom.get( 'f_contact_org_del_contact_id' ).value = <?php echo $id; ?>;
	  show( 'contact_org_del_popup' );
	}
	function delete_prj( prj_id  )
	{
	  Dom.get( 'f_contact_prj_del_prj_id' ).value = prj_id;
	  Dom.get( 'f_contact_prj_del_contact_id' ).value = <?php echo $id; ?>;
	  show( 'contact_prj_del_popup' );
	}
</script>

 <!-- <h3 class="underline">Activities and Interviews</h3> -->

<div id='view_interview_list'>
<?

// get count from DB
//$sql = "SELECT COUNT( DISTINCT pk_id ) AS total
//        FROM interviews
//        WHERE fk_contact_id=".$id;
//$result = mysql_query( $sql );
//$res = mysql_fetch_assoc( $result );
//
//$total = $res['total'];
//
//$sql = "SELECT i.pk_id AS id, i.*, CONCAT_WS( ' ',ui.first_name,ui.last_name) AS analyst,  CONCAT(p.name, ' (', o.name, ')') AS proj_name, (COUNT( ip.fk_project_id ) - 1) AS proj_count, p.pk_id AS proj_id
//        FROM interviews AS i
//          LEFT JOIN user_info AS ui ON ui.pk_id=i.fk_user_id
//          LEFT JOIN interview_projects AS ip ON ip.fk_interview_id=i.pk_id
//          LEFT JOIN projects AS p ON p.pk_id=ip.fk_project_id
//          LEFT JOIN organizations AS o ON o.pk_id=p.fk_client_id
//        WHERE i.fk_contact_id=".$id."
//        GROUP BY id
//        ORDER BY i.int_number ASC, i.int_date DESC";
//
//$result = mysql_query( $sql );
//
//
//while( $res = mysql_fetch_assoc( $result ) )
//{
//  if( $res['is_activity'] )
//  {
//    $val = '&nbsp;&nbsp;&nbsp;'.CORE_date($res['int_date'], F_DATE_HTM).'&nbsp;&nbsp;'.
//          CORE_encode( $res['notes'], F_HTM, F_SQL );
//
//    $del = '';
//    if( $USER->get('level') >= 5 )
//      $del = '<a href="javascript:delete_act('.$res['id'].')" title="delete" style="float:right"><img width="16" height="16" src="img/icons/trash.png"/></a>';
//
//    echo '<div style="margin-left:30px;"><div class="interview_item">'.$del.'
//     <span class="org_name activity"><a href="activities/view/'.$res['id'].'">'.$form->get( 'source_id' ).'-'.$res['int_number'].'</a>'.$val.$proj.'</span>'.SP_DIV.'</div></div>';
//  }
//  else
//  {
//    $val = '&nbsp;&nbsp;&nbsp;interviewed '.CORE_date($res['int_date'], F_DATE_HTM).' by&nbsp;&nbsp;'.
//         '<span class="img_small user">'.
//          CORE_encode( $res['analyst'], F_HTM, F_SQL ).'</span>';
//
//    $export = '<a href="interviews/export_word/'.$res['id'].'" style="float:right" class="h_link img export_word">Export</a>';
//    $email = '<a href="javascript:show_email('.$res['id'].');" style="float:right" class="h_link img email">Email</a>';    
//    
//    $del = '';
//    if( $USER->get('level') >= 5 )
//      $del = '<a href="javascript:delete_int('.$res['id'].')" title="delete" style="float:right"><img width="16" height="16" src="img/icons/trash.png"/></a>';
//
//    $proj = CORE_encode( $res['proj_name'], F_HTM, F_SQL );
//    if( $res['proj_id'] )
//      $proj = '&nbsp;&nbsp;&nbsp;for&nbsp;&nbsp;<a class="img_small proj" href="projects/view/'.$res['proj_id'].'">'.$proj.'</a>';
//    if( $res['proj_count'] > 0 )
//      $proj .= ' and '.$res['proj_count'].' other'.($res['proj_count'] > 1 ? 's' : '');
//
//    echo '<div class="interview_item">'.$export.$email.$del.'
//     <span class="org_name interviews"><a href="interviews/view/'.$res['id'].'">'.$form->get( 'source_id' ).'-'.$res['int_number'].'</a>'.$val.$proj.'</span>'.SP_DIV.'</div>';
//  }
//
//}
//if( !$total )
//{
//  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
//}
//
?>
</div>


  </form>
 </div>
