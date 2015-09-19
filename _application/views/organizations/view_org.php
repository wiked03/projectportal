<?

global $LANG, $USER;

?>

 <div id="org_form">
  <h1>
    <img src="img/icons/big/building.png" />
    <?=$form->get( 'name' )?>
    <a href="organizations/edit/<?=$id?>" class="h_link img edit">Edit Organization</a>
  </h1>

  <form id="f_org">

<div class="left_side">
<?
echo $form->print_item( 'name', 0 );
echo $form->print_item( 'address' );
echo $form->print_item( 'city' );
echo $form->print_item( 'state', 0 );
echo $form->print_item( 'zipcode' );

?>
</div>

<div class="full_width">
<?

if( $form->get( 'notes' ) != '' )
{
  //$form->set( 'notes', nl2br($form->get('notes')) );
  echo $form->print_item( 'notes', 0, 'full', nl2br($form->get('notes')) );
}

?>
</div>

  <h3 class="underline">Sources</h3>


<div id='view_contact_list'>
<?

// get count from DB
$sql = "SELECT COUNT( DISTINCT fk_contact_id ) AS total
        FROM contact_orgs
        WHERE fk_organization_id=".$id;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

$sql = "SELECT c.pk_id AS id, c.*, IF((c.fk_created_by_user=".$USER->get('id')." OR ".($USER->get('level')>2 ? 1 : 0)."), CONCAT_WS( ' ', c.first_name, c.last_name), '".$LANG['source_hidden']."') AS name  
        FROM contacts AS c
          LEFT JOIN contact_orgs AS co ON c.pk_id=co.fk_contact_id
        WHERE co.fk_organization_id=".$id."
        ORDER BY co.is_primary DESC, co.is_current DESC, c.last_name ASC, c.first_name ASC
        LIMIT 10";
$result = mysql_query( $sql );


while( $res = mysql_fetch_assoc( $result ) )
{
  echo '<div class="contact_org">
   <span class="org_name contacts"><a href="contacts/view/'.$res['id'].'">'.$res['name'].'</a></span>'.SP_DIV.'</div>';
}

if( $total > 10 )
{
  $diff = ( $total - 10 );
    echo '<div class="contact_org">
   <span class="org_name more"><a href="contacts/org-'.$id.'">and '.$diff.' other'.(($diff > 1) ? 's' : '').'...</a></span>'.SP_DIV.'</div>';
}
elseif( !$total )
{
  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
}

?>
</div>



  </form>
 </div>

