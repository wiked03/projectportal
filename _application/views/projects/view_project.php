<?

global $LANG, $REGEX, $SESS, $USER;

$my_view = new View( );

// ---------------------------------------------------
$form_data = $_POST;

if( $form_data['submit_form'] == 'upd_del' && $USER->get('level') >= 3 )
{
	if( $form_data['upd_id'] )
	{
		$sql = "DELETE FROM statusupdates WHERE pk_id=".$form_data['upd_id'];
		mysql_query( $sql );
	}
}

if( $form_data['submit_form'] == 'exp_del' && $USER->get('level') >= 3 )
{
	if( $form_data['exp_id'] )
	{
		$sql = "DELETE FROM expenses WHERE pk_id=".$form_data['exp_id'];
		mysql_query( $sql );
	}
}

if( $form_data['submit_form'] == 'confproj_del' && $USER->get('level') >= 3 )
{
	if( $form_data['confproj_id'] )
	{
		$sql = "DELETE FROM conf_projs WHERE pk_id=".$form_data['confproj_id'];
		mysql_query( $sql );
	}
}

if( $form_data['submit_form'] == 'res_del' && $USER->get('level') >= 3 )
{
  if( $form_data['res_id'] )
  {
    $sql = "DELETE FROM resources WHERE pk_id=".$form_data['res_id'];
    mysql_query( $sql );
  }
}

if( $form_data['submit_form'] == 'del_del' && $USER->get('level') >= 3 )
{
  if( $form_data['del_id'] )
  {
    $sql = "DELETE FROM deliverables WHERE pk_id=".$form_data['del_id'];
    mysql_query( $sql );
  }
}

?>

<script type="text/javascript">
$(document).ready(function () {
  $('.expand-interview').click(function(){
      $('.interview-list').slideToggle('fast');
  });
  $('.expand-resource').click(function(){
      $('.resource-list').slideToggle('fast');
  });
  $('.expand-expense').click(function(){
      $('.expense-list').slideToggle('fast');
  });
  $('.expand-confproj').click(function(){
      $('.confproj-list').slideToggle('fast');
  });
  $('.expand-statusupdate').click(function(){
      $('.statusupdate-list').slideToggle('fast');
  });
  $('.expand-deliverable').click(function(){
      $('.deliverable-list').slideToggle('fast');
  });
});
</script>

<?
if( $USER->get('level') >= 3  )
	load_view( 'popups/expense_delete' );

if( $USER->get('level') >= 3  )
	load_view( 'popups/confproj_delete' );

if( $USER->get('level') >= 3  )
  load_view( 'popups/resource_delete' );

if( $USER->get('level') >= 3  )
  load_view( 'popups/deliverable_delete' );

if( $USER->get('level') >= 3  )
	load_view( 'popups/update_delete' );

?>

<script type="text/javascript">
function delete_res( id )
{
  Dom.get( 'f_res_del-res_id' ).value = id;
  show( 'res_del_popup' );
}
function delete_del( id )
{
  Dom.get( 'f_del_del-del_id' ).value = id;
  show( 'del_del_popup' );
}
function delete_exp( id )
{
  Dom.get( 'f_exp_del-exp_id' ).value = id;
  show( 'exp_del_popup' );
}
function delete_confproj( id )
{
  Dom.get( 'f_confproj_del-confproj_id' ).value = id;
  show( 'confproj_del_popup' );
}
function delete_upd( id )
{
  Dom.get( 'f_upd_del-upd_id' ).value = id;
  show( 'upd_del_popup' );
}
</script>

 <div id="project_form">
  <h1>
   <img src="img/icons/big/report.png" />
   <?=$form->get('name')?>
<?
   if( $edit_lock ){
     
    $contractors = $my_view->get_list( 'project_contractors', F_HTM, str_replace('c-', '', $id));
    if($contractors){
        //echo  '<a href="admin/expenses/edit/c-'.$id.'" class="h_link img interview_add">New Expense</a>';
        //echo  '<a href="admin/hours/edit/c-'.$id.'" class="h_link img interview_add">Track Hours</a>';
        //echo  '<a href="admin/hours/report/c-'.$id.'" class="h_link img interview_add">Hours Report</a>';
    }
   // echo  '<a href="admin/resources/add/c-'.$id.'" class="h_link img interview_add">New Contribution</a>';
   // echo  '<a href="admin/statusupdates/add/c-'.$id.'" class="h_link img interview_add">New Status Update</a>';
   // echo  '<a href="admin/deliverables/add/c-'.$id.'" class="h_link img interview_add">New Deliverable</a>';
    echo '<a href="projects/edit/'.$id.'" class="h_link img edit">Edit Project</a>';
   }
?>
  </h1>

  <form name="f_project" id="f_project">

<div class="left_side"><div class="lgcol_left module_box_ltgray"><h2 class="underline">Project Information</h2>
<?

echo $form->print_item( 'id_prefix', 0 );

//echo $form->print_item( 'name', 0 ); //

echo $form->print_item( 'description', 0 );

$org = $my_view->get( 'org_name', $form->get('fk_client_id') );
$org_id = $form->get('fk_client_id' );
if( $org_id )
  $org = '<a class="img_small org" href="organizations/view/'.$org_id.'">'.$org.'</a>';
echo $form->print_item( 'org_search', 0, NULL, $org );

/*
$org2 = $my_view->get( 'org_name', $form->get('fk_target_id') );
$org2_id = $form->get('fk_target_id' );
if( $org2_id )
  $org2 = '<a class="img_small org" href="organizations/view/'.$org2_id.'">'.$org2.'</a>';
echo $form->print_item( 'org_search_target', 0, NULL, $org2 );
*/

/*
$org = $my_view->get( 'contact', $form->get('fk_poc_id') );
$org_id = $form->get('fk_poc_id' );
if( $org_id )
{
  $org = '<a class="img_small contact" href="contacts/view/'.$org_id.'">'.$org.'</a>';
  echo $form->print_item( 'poc_search', 0, NULL, $org );
}*/
echo $form->print_item( 'poc', 0 );

echo $form->print_item( 'is_life_science', 0 );

echo $form->print_item( 'specialty', 0 );

//echo $form->print_item( 'industry', 0 );

?>
</div>

<div class="lgcol_left module_box"><h2 class="underline">Timeline/Status</h2>

<?
$changed_by = '<span class="user_name user">'.$form->get( 'changed_by', F_HTM ).'</span>&nbsp;&nbsp;on&nbsp;&nbsp;'.CORE_date( $form->get( 'last_changed' ), F_DATE_HTM );
echo $form->print_item( 'changed_by', 0, NULL, $changed_by );


echo $form->print_item( 'start', 0, 'date' );

echo $form->print_item( 'end', 0, 'date end_date' );

echo $form->print_item( 'is_active', 0 );

if( $form->get( 'notes' ) != '' )
  echo $form->print_item( 'notes', 0, 'full' );

?>

</div>

</div>


<div class="right_side module_box_gray"><h2 class="underline">Project Team</h2>

<?

echo $form->print_item( 'bd_poc', 0 );

$dir = $form->get( 'fk_dir_id' );
if( $dir )
	$dir = '<span class="img_small user">'.$my_view->get( 'user', $dir ).'</span>';
else 
	$dir = 'None';
echo $form->print_item( 'fk_dir_id', 0, NULL, $dir );

$pm = $form->get( 'fk_pm_id' );
if( $pm )
 	$pm = '<span class="img_small user">'.$my_view->get( 'user', $pm ).'</span>';
else
	$pm = 'None';
echo $form->print_item( 'fk_pm_id', 0, NULL, $pm );

$analysts = $form->get( 'analysts' );
if( $analysts )
{
  $analysts = explode( '.', $form->get( 'analysts' ) );
  foreach( $analysts as $analyst )
  { 
    $val[] = '<span class="img_small user">'.$my_view->get( 'user', $analyst ).'</span>';
  }
  $val = implode( ', ', $val );
}
else
  $val = 'None';
echo $form->print_item( 'analysts', 0, NULL, $val );

//$collectors = $form->get( 'collectors' );
//if( $collectors )
//{
//	$collectors = explode( '.', $form->get( 'collectors' ) );
//	foreach( $collectors as $collector )
//	{
//		$val_collectors[] = '<span class="img_small user">'.$my_view->get( 'user', $collector ).'</span>';
//	}
//	$val_collectors = implode( ', ', $val_collectors );
//}
//else
//	$val_collectors = 'None';
//echo $form->print_item( 'collectors', 0, NULL, $val_collectors );

$contractors = $form->get( 'contractors' );
if( $contractors )
{
  $contractors = explode( '.', $form->get( 'contractors' ) );
  foreach( $contractors as $contractor )
  { 
    $val_contractors[] = '<span class="img_small user">'.$my_view->get( 'contact', $contractor ).'</span>';
  }
  $val_contractors = implode( ', ', $val_contractors );
}
else
  $val_contractors = 'None';
echo $form->print_item( 'contractors', 0, NULL, $val_contractors );
?></div> 



<div class="right_side module_box_dkgray"> <h2 class="underline">Financials</h2>
<?
/*
$conferences = $form->get( 'conferences' );
if( $conferences )
{
	$conferences = explode( '.', $form->get( 'conferences' ) );
	foreach( $conferences as $conference )
	{
		$val_conferences[] = $my_view->get( 'conference', $conference, F_HTM );
	}
	$val_conferences = implode( ', ', $val_conferences );
}
else
	$val_conferences = 'None';
echo $form->print_item( 'conferences', 0, NULL, $val_conferences );
*/

echo $form->print_item( 'profit', 0 );

echo $form->print_item( 'sum_conferences_value', 0 );

echo $form->print_item( 'value', 0 );

echo $form->print_item( 'total_value', 0 );
?>

<div class="smcol_left">

<? echo $form->print_item( 'sum_expenses', 0 );

echo $form->print_item( 'pct_spent', 0 ); 

echo $form->print_item( 'months', 0 );

?>
</div><div class="smcol_right">
<?
echo $form->print_item( 'hourly_rate', 0 );

echo $form->print_item( 'estimated_hours', 0 );

echo $form->print_item( 'monthly_hours', 0 );

?></div>
</div>



<!-- Expenses  -->

<?
if( $USER->get('level') >= 3 )
{
?>

  <div>
  <div class="expand-expense">
      <h3 class="underline"><a>Expenses</a></h3>
  </div>
  <div style="float:right;">
  <?
    $contractors = $my_view->get_list( 'project_contractors', F_HTM, str_replace('c-', '', $id));
    if($contractors){
        echo  '<a href="admin/expenses/edit/c-'.$id.'" class="h_link img interview_add">New Expense</a>';
    }
  ?>
  </div>


<div id='view_expenses_list' class="expense-list">
<?

// get count from DB
$sql = "SELECT COALESCE(COUNT( DISTINCT pk_id ), 0) AS total
        FROM expenses AS exp
        WHERE exp.fk_project_id=".$id;
//echo $sql;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

$sql =  "SELECT * FROM (SELECT exp.pk_id AS id, exp.int_date, exp.int_date AS min_date,
       CONCAT_WS(' ', co.first_name, co.last_name) AS contractor, co.pk_id AS contractor_id, 
               p.name AS proj_name, p.pk_id AS proj_id, 
               CONCAT('$', FORMAT(int_amount, 2)) as int_amount,
               int_amount as amount,
			   other_expense
        FROM expenses AS exp
          LEFT JOIN contacts AS co ON co.pk_id=exp.fk_contractor_id
          LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
        WHERE exp.fk_project_id=".$id."
        GROUP BY id
        UNION
        SELECT '0' as id, 
          CONCAT ('(', min(int_date), ' - ', max(int_date), ')') as int_date,
           min(int_date) as min_date,
          CONCAT(count(1), ' Honoraria') AS contractor, 
                GROUP_CONCAT(i.pk_id SEPARATOR '.') AS contractor_id, 
                p.name AS proj_name, p.pk_id AS proj_id,
                CONCAT('$', FORMAT(SUM(REPLACE(rate, '$', '')), 2)) as int_amount,
                SUM(REPLACE(rate, '$', '')) as amount,
				0 as other_expense
           FROM interviews i
          INNER JOIN interview_projects ip ON i.pk_id = ip.fk_interview_id
           LEFT JOIN projects p ON p.pk_id = ip.fk_project_id
         WHERE ip.fk_project_id = ".$id." AND paid = 1) AS t
        ORDER BY min_date";

//echo $sql;
$result = mysql_query( $sql );

function get_expense_line($row){
	if($row['other_expense']=='0'){
		return '&nbsp;&nbsp;&nbsp;'.$row['int_date'].' expense of&nbsp;&nbsp;'.
	           '<a class="img_small contacts" href="/interviews/iid-'.$row['contractor_id'].'">'.
	            CORE_encode( $row['contractor'], F_HTM, F_SQL ).'</a>';
	} else {
		return '&nbsp;&nbsp;&nbsp;'.$row['int_date'].' other expense.';
	}
}

$has_honoraria = False;
while( $res = mysql_fetch_assoc( $result ) )
{
  if($res['id']=='0'){
	/*
    $val = '&nbsp;&nbsp;&nbsp;'.$res['int_date'].' expense of&nbsp;&nbsp;'.
           '<a class="img_small contacts" href="/interviews/iid-'.$res['contractor_id'].'">'.
            CORE_encode( $res['contractor'], F_HTM, F_SQL ).'</a>';
	*/
	$val = get_expense_line($res);

    if($res['amount']>0){
      echo '<div class="interview_item">
      <span class="org_name"><div class="amount"><a href="admin/expenses/edit/'.$res['id'].'">'.$res['int_amount'].'</a></div>'.$val.'</span>'.SP_DIV.'</div>';
       $has_honoraria = True;
    } 

  } else {
	/*
    $val = '&nbsp;&nbsp;&nbsp;'.$res['int_date'].' expense of&nbsp;&nbsp;'.
           '<a class="img_small contacts" href="contacts/view/'.$res['contractor_id'].'">'.
            CORE_encode( $res['contractor'], F_HTM, F_SQL ).'</a>';
    */
    $val = get_expense_line($res);

      echo '<div class="interview_item">
      <span class="org_name"><div class="amount"><a href="admin/expenses/edit/'.$res['id'].'">'.$res['int_amount'].'</a></div>'.$val.'</span>&nbsp;&nbsp;&nbsp;<a href="javascript:delete_exp('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>'.SP_DIV.'</div>';
      
  }
}

if( $total > 25 )
{
  $diff = ( $total - 25 );
    echo '<div class="interview_item">
   <span class="org_name more"><a href="admin/expenses/prj-'.$id.'">and '.$diff.' other'.(($diff > 1) ? 's' : '').'...</a></span>'.SP_DIV.'</div>';
}
elseif( !$total)
{
  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
}

?>
</div>


<?
}
?>

<!-- Confproj  -->

<?
if( $USER->get('level') >= 3 )
{
?>

  <div>
  <div class="expand-confproj">
      <h3 class="underline"><a>Conferences</a></h3>
  </div>
  <div style="float:right;">
  <?
    echo  '<a href="admin/confprojs/edit/c-'.$id.'" class="h_link img interview_add">Add Conference</a>';
  ?>
  </div>


<div id='view_confproj_list' class="confproj-list">
<?

// get count from DB
$sql = "SELECT COALESCE(COUNT( DISTINCT pk_id ), 0) AS total
        FROM conf_projs AS exp
        WHERE exp.fk_project_id=".$id;
//echo $sql;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

$sql =  "SELECT exp.pk_id AS id, exp.num_days,
       co.name AS conference, co.pk_id AS conference_id, 
               p.name AS proj_name, p.pk_id AS proj_id, 
               CONCAT('$', FORMAT(int_amount, 2)) as int_amount,
               int_amount as amount,
	           (SELECT group_concat(CONCAT_WS(' ', TRIM(ui.first_name), TRIM(ui.last_name)) SEPARATOR ', ') 
				  FROM user_conf_projs AS uc
	              LEFT JOIN user_info AS ui ON uc.fk_user_id = ui.pk_id
	             WHERE uc.fk_conf_proj_id = exp.pk_id
	              group by exp.pk_id) AS attendees
        FROM conf_projs AS exp
          LEFT JOIN conferences AS co ON co.pk_id=exp.fk_conference_id
          LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
        WHERE exp.fk_project_id=".$id."
        GROUP BY id";

//echo $sql;
$result = mysql_query( $sql );

function get_confproj_line($row){
	return '&nbsp;&nbsp;&nbsp;'.$row['int_date'].' expense of&nbsp;&nbsp;'.
		'<a class="img_small contacts" href="/interviews/iid-'.$row['conference_id'].'">'.
		CORE_encode( $row['conference'], F_HTM, F_SQL ).'</a>';
}

echo '<div class="table_frame">';
echo '<table class="list_table no_foot" style="margin-left:2cm; width:90%;">';
echo '<thead><tr>';
  echo '<th style="width:60px; font-weight:bold; text-align:center;">Conference</th>';
  echo '<th style="width:30px; font-weight:bold; text-align:center;">Value</th>';
  echo '<th style="width:30px; font-weight:bold; text-align:center;">Days</th>';
  echo '<th style="width:100px; font-weight:bold; text-align:center;">Atendees</th>';
  echo '<th style="width:30px; font-weight:bold; text-align:center;">Actions</th>';
echo '</tr></thead>';
while( $res = mysql_fetch_assoc( $result ) )
{
	
	echo '<tr>';
  	$val = get_confproj_line($res);
  	echo '<td><a href="admin/confprojs/edit/'.$res['id'].'">'.CORE_encode( $res['conference'], F_HTM, F_SQL ).'</a></td>';
  	echo '<td style="text-align:right;"> $'.$res['amount'].'</td>';
  	echo '<td>'.$res['num_days'].'</td>';
  	echo '<td>'.$res['attendees'].'</td>';
  	echo '<td><a href="admin/confprojs/edit/'.$res['id'].'" title="edit"><img width="16" height="16" src="img/icons/pencil.png"/></a><a href="javascript:delete_confproj('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a></td>';
  	echo '</tr>';
}
echo '</table>';
echo '</div>';

if( $total > 25 )
{
  $diff = ( $total - 25 );
    echo '<div class="interview_item">
   <span class="org_name more"><a href="admin/confproj/prj-'.$id.'">and '.$diff.' other'.(($diff > 1) ? 's' : '').'...</a></span>'.SP_DIV.'</div>';
}
elseif( !$total)
{
  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
}

?>
</div>


<?
}
?>

<!-- Contributions  -->

<?
if( $USER->get('level') >= 10 ) // disable contributios
{
?>

  <div>
  <div class="expand-resource">
      <h3 class="underline"><a>Contributions</a></h3>
  </div>
  <div style="float:right;">
  <?
     echo  '<a href="admin/resources/add/c-'.$id.'" class="h_link img interview_add">New Contribution</a>';
  ?>
  </div>

<div id='view_resources_list' class="resource-list">
<?

// get count from DB
$sql = "SELECT COUNT( DISTINCT pk_id ) AS total
        FROM resources AS exp
        WHERE exp.fk_project_id=".$id;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

$sql = "SELECT exp.pk_id AS id, exp.*, 
               CONCAT_WS(' ', co.first_name, co.last_name) AS user, co.pk_id AS user_id, 
               CONCAT_WS(' ', con.first_name, con.last_name) AS contractor, con.pk_id AS contractor_id, 
               p.name AS proj_name, p.pk_id AS proj_id, 
               CONCAT(FORMAT(effort, 0), '%') as effort,
               QUARTER(int_start_date) as start_quarter, YEAR(int_start_date) as start_year
        FROM resources AS exp
          LEFT JOIN user_info AS co ON co.pk_id=exp.fk_user_id
          LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
          LEFT JOIN contacts AS con ON con.pk_id=exp.fk_contractor_id
        WHERE exp.fk_project_id=".$id."
        GROUP BY id
        ORDER BY start_year, start_quarter";
//echo $sql;

$result = mysql_query( $sql );

if( !$total )
{
  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
} 
else
{
  echo '<div class="table_frame">';
  echo '<table class="list_table no_foot" style="margin-left:2cm; width:90%;">';
  echo '<thead><tr>';
    echo '<th style="width:30px; font-weight:bold; text-align:center;">Year</th>';
    echo '<th style="width:30px; font-weight:bold; text-align:center;">Quarter</th>';
    echo '<th style="width:100px; font-weight:bold; text-align:center;">User</th>';
    echo '<th style="width:40px; font-weight:bold; text-align:center;">Start Date</th>';
    echo '<th style="width:40px; font-weight:bold; text-align:center;">End Date</th>';
    echo '<th style="width:40px; font-weight:bold; text-align:center;">% Effort</th>';
    echo '<th style="width:160px; font-weight:bold; text-align:center;">Notes</th>';
    echo '<th style="width:30px; font-weight:bold; text-align:center;">Actions</th>';
  echo '</tr></thead>';

  $c = true;
  while( $res = mysql_fetch_assoc( $result ) )
  {
      $on_click = 'onclick="Dom.goto(\'admin/resources/edit/'.$res['id'].'\')"';

      echo '<tr '.(($c = !$c)?' class="alt"':'').'.>';
      echo '<td '.$on_click.'>'.$res['start_year'].'</td>';
      echo '<td '.$on_click.'>'.$res['start_quarter'].'</td>';

      if(is_null($res['user_id'])){
        echo '<td '.$on_click.'><a class="img_small contacts" href="contacts/edit/'.$res['contractor_id'].'">'.
              CORE_encode( $res['contractor'], F_HTM, F_SQL ).'</a></td>';
      } else {
        echo '<td '.$on_click.'><a class="img_small contacts" href="admin/users/edit/'.$res['user_id'].'">'.
              CORE_encode( $res['user'], F_HTM, F_SQL ).'</a></td>';
      }
      echo '<td '.$on_click.'>'.CORE_date($res['int_start_date'], F_DATE_HTM).'</td>';
      echo '<td '.$on_click.'>'.CORE_date($res['int_end_date'], F_DATE_HTM).'</td>';
      echo '<td '.$on_click.'>'.$res['effort'].'</td>';
      echo '<td '.$on_click.'>'.$res['notes'].'</td>';
      echo '<td><a href="javascript:delete_res('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a></td>';

      echo '</tr>';
  }
  echo '</table>';
  echo '</div>';
}

?>
</div>

<?
}
?>

<!-- StatusUpdates  -->

<?
if( $USER->get('level') >= 10 ) // disable status
{
?>

  <div>
  <div class="expand-statusupdate">
      <h3 class="underline"><a>Status Updates</a></h3>
  </div>
  <div style="float:right;">
  <?
     echo  '<a href="admin/statusupdates/add/c-'.$id.'" class="h_link img interview_add">New Status Update</a>';
  ?>
  </div>

<div id='view_statusupdates_list' class="statusupdate-list">
<?

// get count from DB
$sql = "SELECT COUNT( DISTINCT pk_id ) AS total
        FROM statusupdates AS exp
        WHERE exp.fk_project_id=".$id;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

$sql = "SELECT exp.pk_id AS id, exp.*, 
               p.name AS proj_name, p.pk_id AS proj_id
        FROM statusupdates AS exp
          LEFT JOIN user_info AS co ON co.pk_id=exp.fk_user_id
          LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
          LEFT JOIN contacts AS con ON con.pk_id=exp.fk_contractor_id
        WHERE exp.fk_project_id=".$id."
        GROUP BY id
        ORDER BY int_start_date DESC, id ASC";
//echo $sql;

$result = mysql_query( $sql );

if( !$total )
{
  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
} 
else
{
  echo '<div class="table_frame">';
  echo '<table class="list_table no_foot" style="margin-left:2cm; width:90%;">';
  echo '<thead><tr>';
    echo '<th style="width:30px; font-weight:bold; text-align:center;">Date</th>';
    echo '<th style="width:50px; font-weight:bold; text-align:center;">Status</th>';
    echo '<th style="width:100px; font-weight:bold; text-align:center;">Notes</th>';
    echo '<th style="width:40px; font-weight:bold; text-align:center;">Resolved</th>';
    echo '<th style="width:40px; font-weight:bold; text-align:center;">Actions</th>';
  echo '</tr></thead>';

  $c = true;
  while( $res = mysql_fetch_assoc( $result ) )
  {
      $on_click = 'onclick="Dom.goto(\'admin/statusupdates/edit/'.$res['id'].'\')"';

      echo '<tr '.(($c = !$c)?' class="alt"':'').'.>';
      echo '<td '.$on_click.'>'.CORE_date($res['int_start_date'], F_DATE_HTM).'</td>';
      if ($res['concern']==0 || $res['status']==1) {
        echo '<td '.$on_click.'><img width="16px" height="16px" src="../img/icons/success_20.png">'.$LANG['concerns'][$res['concern']].'</td>';
      } else {
        if ($res['concern']==1) {
          echo '<td '.$on_click.'><img width="16px" height="16px" src="../img/icons/warning.png">'.$LANG['concerns'][$res['concern']].'</td>';
        } else {
          if ($res['concern']==2) {
            echo '<td '.$on_click.'><img width="16px" height="16px" src="../img/icons/error.png">'.$LANG['concerns'][$res['concern']].'</td>';
          }
        }
      }

      
      echo '<td '.$on_click.'>'.$res['notes'].'</td>';
      if($res['status']==1){
        echo '<td '.$on_click.'>Yes - '.CORE_date($res['resolved_date'], F_DATE_HTM).'</td>';
      } else {
        echo '<td '.$on_click.'>No</td>';
      }

      echo '<td><a href="admin/statusupdates/resolve/'.$res['id'].'" title="resolve">Resolve</a><a class="h_link img edit" href="admin/statusupdates/edit/'.$res['id'].'">'.
      		CORE_encode( $res['user'], F_HTM, F_SQL ).'</a>'.
      		'<a href="javascript:delete_upd('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a></td>';
      
      echo '</tr>';
  }
  echo '</table>';
  echo '</div>';
}

?>
</div>

<?
}
?>

<!-- Deliverables  -->

<?
if( $USER->get('level') >= 3 )
{
?>

  <div>
  <div class="expand-deliverable">
      <h3 class="underline"><a>Deliverables</a></h3>
  </div>
  <div style="float:right;">
  <?
      echo  '<a href="admin/deliverables/add/c-'.$id.'" class="h_link img interview_add">New Deliverable</a>';
  ?>
  </div>

<div id='view_deliverables_list' class="deliverable-list">
<?

// get count from DB
$sql = "SELECT COUNT( DISTINCT pk_id ) AS total
        FROM deliverables AS exp
        WHERE exp.fk_project_id=".$id;
$result = mysql_query( $sql );
$res = mysql_fetch_assoc( $result );

$total = $res['total'];

$sql = "SELECT exp.pk_id AS id, exp.*, 
               p.name AS proj_name, p.pk_id AS proj_id
        FROM deliverables AS exp
          LEFT JOIN projects AS p ON p.pk_id=exp.fk_project_id
        WHERE exp.fk_project_id=".$id."
        GROUP BY id
        ORDER BY int_start_date desc, id asc";
//echo $sql;

$result = mysql_query( $sql );

if( !$total )
{
  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
} 
else
{
  echo '<div class="table_frame">';
  echo '<table class="list_table no_foot" style="margin-left:2cm; width:90%;">';
  echo '<thead><tr>';
    echo '<th style="width:40px; font-weight:bold; text-align:center;">Date</th>';
    echo '<th style="width:60px; font-weight:bold; text-align:center;">Client Interaction</th>';
    echo '<th style="width:60px; font-weight:bold; text-align:center;">Type</th>';
    echo '<th style="width:100px; font-weight:bold; text-align:center;">Notes</th>';
    echo '<th style="width:30px; font-weight:bold; text-align:center;">Actions</th>';
  echo '</tr></thead>';

  $c = true;
  while( $res = mysql_fetch_assoc( $result ) )
  {
      $on_click = 'onclick="Dom.goto(\'admin/deliverables/edit/'.$res['id'].'\')"';

      echo '<tr '.(($c = !$c)?' class="alt"':'').'.>';
      echo '<td '.$on_click.'>'.CORE_date($res['int_start_date'], F_DATE_HTM).'</td>';
      echo '<td '.$on_click.'>'.$LANG['clientinteraction'][$res['clientinteraction']].'</td>';
      echo '<td '.$on_click.'>'.$LANG['deliverable_type'][$res['type']].'</td>';
      echo '<td '.$on_click.'>'.$res['notes'].'</td>';
      echo '<td><a class="h_link img edit" href="admin/deliverables/edit/'.$res['id'].'">'.
            CORE_encode( $res['user'], F_HTM, F_SQL ).'</a>'.
            '<a href="javascript:delete_del('.$res['id'].')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a></td>';
      echo '</tr>';
  }

  echo '</table>';
  echo '</div>';
}

?>
</div>

<?
}
?>


<!-- Interviews  -->

<?
//if( $USER->get('level') >= 0 ) // for all
//{
?>
  <!--<div>
  <h3 class="underline"><div class="expand-interview"><a>Interviews</a></p></h3>
  </div> -->

<!--<div id='view_interview_list' class="interview-list"> -->
<?
//
//// get count from DB
//$sql = "SELECT COUNT( DISTINCT fk_interview_id ) AS total
//        FROM interview_projects AS ip
//          LEFT JOIN interviews AS i ON ip.fk_interview_id=i.pk_id
//        WHERE fk_project_id=".$id."
//          AND NOT is_activity";
//$result = mysql_query( $sql );
//$res = mysql_fetch_assoc( $result );
//
//$total = $res['total'];
//
//$sql = "SELECT i.pk_id AS id, i.*, CONCAT_WS( ' ',ui.first_name,ui.last_name) AS analyst, p.name AS proj_name, (COUNT( ip.fk_project_id ) - 1) AS proj_count, p.pk_id AS proj_id, c.pk_id AS contact_id, CONCAT_WS( ' ',c.first_name,c.last_name) AS contact_name, c.*
//        FROM interviews AS i
//          LEFT JOIN user_info AS ui ON ui.pk_id=i.fk_user_id
//          LEFT JOIN interview_projects AS ip ON ip.fk_interview_id=i.pk_id
//          LEFT JOIN projects AS p ON p.pk_id=ip.fk_project_id
//          LEFT JOIN contacts AS c ON c.pk_id=i.fk_contact_id
//        WHERE ip.fk_project_id=".$id."
//          AND NOT is_activity
//        GROUP BY id
//        ORDER BY i.int_date DESC
//        LIMIT 10";
//
//$result = mysql_query( $sql );
//
//
//while( $res = mysql_fetch_assoc( $result ) )
//{
//  $val = '&nbsp;&nbsp;&nbsp;'.CORE_date($res['int_date'], F_DATE_HTM).' interview of&nbsp;&nbsp;'.
//         '<a class="img_small contacts" href="contacts/view/'.$res['contact_id'].'">'.
//          CORE_encode( $res['contact_name'], F_HTM, F_SQL ).'</a>';
//
//  $val .= '&nbsp;&nbsp;&nbsp;by&nbsp;&nbsp;'.
//         '<span class="img_small user">'.
//          CORE_encode( $res['analyst'], F_HTM, F_SQL ).'</span>';
//
//  echo '<div class="interview_item">
//   <span class="org_name interviews"><a href="interviews/view/'.$res['id'].'">'.$LANG['source_types_short'][$res['type']].'-'.sprintf( '%03d', $res['fk_contact_id'] ).'-'.$res['int_number'].'</a>'.$val.'</span>'.SP_DIV.'</div>';
//}
//
//if( $total > 10 )
//{
//  $diff = ( $total - 10 );
//    echo '<div class="interview_item">
//   <span class="org_name more"><a href="interviews/prj-'.$id.'">and '.$diff.' other'.(($diff > 1) ? 's' : '').'...</a></span>'.SP_DIV.'</div>';
//}
//elseif( !$total )
//{
//  echo '<div class="contact_org"><span class="org_name">None</span>'.SP_DIV.'</div>';
//}
//
//?>
<!--</div> -->

<?
//}
?>

  </form>
 </div>
