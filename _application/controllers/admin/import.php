<?
$SESS->require_login( $USER, PATH_SELF, 5 );

load_model( 'user_info' );
load_model( 'contact' );
load_model( 'organization' );
load_model( 'project' );
load_model( 'interview' );
load_lib( 'email' );

// -----------------------------
function csv_get_one_line( $data, $keys=NULL )
{
  $delim = ',';
  $qual = '"';

  $len = strlen( $data );

  if( !$len )
    return NULL;

  $inside = 0;
  $key_index = 0;

  for( $i = 0; $i < $len; $i++ )
  { 
    if( $data[$i] == $delim && !$inside )
    { 
      if( $keys )
        $out[ $keys[$key_index++] ] = $word;
      else
        $out[] = $word;
      $word = '';
    }
    elseif( $data[$i] == $qual )
      $inside = !$inside;
    else
      $word .= $data[$i];
  }
  if( $keys )
    $out[ $keys[$key_index] ] = $word;
  else
    $out[] = $word;

  return $out;
}

// -----------------------------
function map_data( $data, $key_map )
{
  foreach( $key_map as $db => $csv )
    $out[$db] = $data[$csv];

  return $out;
}

// -----------------------------
function parse_csv( $data, $key_map=NULL )
{ 
  // make sure the line breaks are the correct format
  $data = ereg_replace( chr(13).chr(10), "\r\n", $data );
  $data = ereg_replace( chr(13), "\r\n", $data );
  $data = ereg_replace( "\r\n\n", "\r\n", $data );

  $delim = ','; $qual = '"'; $newline = "\r\n";
  $data = explode( $newline, $data );

  $keys = csv_get_one_line( $data[0] );

  for( $i = 1; $data[$i] != ''; $i++ )
  {
    $vals = csv_get_one_line( $data[$i], $keys );
    
    if( $key_map )
      $values[] = map_data( $vals, $key_map );
    else
      $values[] = $vals;
  }

  return $values;
}



function map_find_key( $item, $maps, $multi=0 )
{

  $replace = '/(\.|\,| )/';
  $item = preg_replace( $replace, '', $item );

  if( !$multi )
    $maps = array( $maps );

  foreach( $maps as $map )
  {
    if( isset( $map[$item] ) )
      return $item;

    foreach( $map as $key => $data )
    {
      if( strtolower(preg_replace( $replace, '', $data )) == strtolower($item) )
        return $key;
    }
  }

  return '';
}


function find_item( $field, $value, $table )
{
    $sql = "SELECT pk_id AS id
            FROM ".$table."
            WHERE ".$field." LIKE ".CORE_encode($value, F_SQL);

    $result = mysql_query( $sql );
    if( $res = mysql_fetch_assoc( $result ) )
    {
      return $res['id'];
    }
    else
      return 0;

}


function find_user( $full_name )
{

  $user = explode( ' ', $full_name );

    $sql = "SELECT ui.pk_id AS id
            FROM user_info AS ui
              LEFT JOIN users AS u ON ui.pk_id=u.pk_id
            WHERE ui.first_name LIKE ".CORE_encode($user[0], F_SQL)." AND ui.last_name LIKE ".CORE_encode($user[1], F_SQL);

    $result = mysql_query( $sql );
    if( $res = mysql_fetch_assoc( $result ) )
    {
      return $res['id'];
    }
    else
    {
      $user_data['temp'] = 1;
      $user_data['imported'] = 1;
      $user_data['active'] = 0;
      $user_data['level'] = 2;
      $user_data['first_name'] = $user[0];
      $user_data['last_name'] = $user[1];
      $user_data['username'] = $user[0].$user[1];

      if( !$user_data['username'] )
        return NULL;

      $my_user = new User( );
      $my_user_info = new User_info( );

      $my_user->set_data( $user_data );


      $user_data['pk_id'] = $my_user->write_back( );
      $my_user_info->set_data( $user_data );
      return $my_user_info->write_back( );
    }
}




// Sign up script
// ---------------------------------------------------
if( $_POST['submit_form'] && isset($_FILES['import_file']['tmp_name']) )
{
  if( isset($_FILES['import_file']['tmp_name']) )
  { 
    $filename = $_FILES['import_file']['tmp_name'];
  }

  $file = file_get_contents($filename);

  // database value       =>  csv value
  $key_map['first_name']   = 'First Name';
  $key_map['last_name']    = 'Last Name';
  $key_map['email1']       = 'Email';
  $key_map['phone1']       = 'Phone';
  $key_map['phone1_type']  = 'Phone Type';
  $key_map['phone2']       = 'Phone2';
  $key_map['phone2_type']  = 'Phone2 Type';
  $key_map['title']        = 'Title';
  $key_map['recontact']    = 'Source open to recontact? (Y/N)';
  $key_map['notes']        = 'Notes (What is key to know about this source?)';
  $key_map['org_name']     = 'Institution/Company';
  $key_map['city']         = 'City';
  $key_map['state']        = 'State';
  $key_map['country']      = 'Country';
  $key_map['specialty']    = 'Therapeutic Area';
  $key_map['type']         = 'Contact Type';

  $key_map['proj_name']    = 'Project';
  $key_map['client']       = 'Client';
  $key_map['proj_mgr']     = 'Project Manager';

  $key_map['analyst']      = 'Person who contacted source';
  $key_map['int_date']     = 'Date Contacted';
  $key_map['method']       = 'Email or Phone';
  $key_map['approach']     = 'Approach';
  $key_map['rate']         = 'Free or Paid Interview? (Amount)';

  $key_map['bg1']          = 'Area of Expertise 1';
  $key_map['bg2']          = 'Area of Expertise 2';
  $key_map['bg3']          = 'Other?';

//  $key_map['']     = '';

  $data = parse_csv( $file, $key_map );

  $country_map = array( 'US'=>'USA', 'DE'=>'GER', 'GB'=>'UK', 'NL'=>'NETH', 'BE'=>'BELG', 'NO'=>'NOR', 'NO'=>'Norway2', 'NO'=>'Norway3','AU'=>'AUS', 'CA'=>'CAN', 'TW'=>'TAI' );


  $import_count = 0;
  // ---------------------------------------------------
  // Create Users
  foreach( $data as $item )
  {
    if( $item['bg1'] )
      $item['background'] = $item['bg1'];
    if( $item['bg2'] )
    {
      if( $item['background'] )
        $item['background'] .= "\r\n"."\r\n";
      $item['background'] .= $item['bg2'];
    }
    if( $item['bg3'] )
    {
      if( $item['background'] )
        $item['background'] .= "\r\n"."\r\n";
      $item['background'] .= $item['bg3'];
    }


    $item['imported'] = 1;
    $item['phone1'] = CORE_phone( $item['phone1'], F_SQL );
    $item['int_date'] = CORE_date( $item['int_date'], F_DATE_SQL );

    $item['first_name'] = ucfirst($item['first_name']);

    $names = explode( ',', $item['last_name'] );
    $item['last_name'] = ucfirst($names[0]);

    $degrees = array();
    for( $i=1; isset( $names[$i] ); $i++ )
    {
      $deg = map_find_key( $names[$i], $LANG['degrees'] );
      if( $deg )
        $degrees[] = $deg;
    }

    if( $degrees )
      $item['degree'] = implode( '.', $degrees );
    else
      $item['degree'] = '10';

    $item['specialty'] = map_find_key( $item['specialty'], $LANG['specialties'] );  
    $item['type'] = map_find_key( $item['type'], $LANG['source_types_full'] );

    if( !$item['type'] )
      $item['type'] = 15;

    $item['phone1_type'] = map_find_key( $item['phone1_type'], $LANG['phone_types'] );
    $item['phone2_type'] = map_find_key( $item['phone2_type'], $LANG['phone_types'] );

    if( !$item['phone1_type'] )
      $item['phone1_type'] = 1;
    if( !$item['phone2_type'] )
      $item['phone2_type'] = 1;

    if( strtoupper($item['recontact']) == 'NO' )
      $item['recontact'] = 0;
    else
      $item['recontact'] = 1;

    $item['country'] = strtoupper( map_find_key( $item['country'], array($LANG['countries'], $country_map), 1 ) );
    $item['state'] = strtoupper( map_find_key( $item['state'], $LANG['states'] ) );

    $item['method'] = strtoupper( map_find_key( $item['method'], $LANG['contact_method'] ) );
    $item['approach'] = strtoupper( map_find_key( $item['approach'], $LANG['approach'] ) );

    // find analyst
    $item['fk_user_id'] = find_user( $item['analyst'] );

    // find client
    $client_id = find_item( 'name', $item['client'], 'organizations' );
    if( !$client_id )
    {
      $my_org = new Organization( );

      $org['name'] = $item['client'];
      $org['imported'] = 1;

      $my_org->set_data( $org );
      $client_id = $my_org->write_back( );
    }

    // find project
    $proj_id = find_item( 'name', $item['proj_name'], 'projects' );
    if( !$proj_id )
    {
      // find project manager
      $pm_id = find_user( $item['proj_mgr'] );

      $my_proj = new Project( );

      $proj['name'] = $item['proj_name'];
      $proj['fk_pm_id'] = $pm_id;
      $proj['fk_client_id'] = $client_id;
      $proj['imported'] = 1;
      $proj['is_active'] = 0;

      $my_proj->set_data( $proj );
      $proj_id = $my_proj->write_back( );
    }

    // assign project to analyst
    $sql = "INSERT INTO user_projects (fk_project_id, fk_user_id) VALUES (".$proj_id.", ".$item['fk_analyst_id'].")";
    mysql_query( $sql );

    // create contact
    if( !$item['first_name'] )
      $item['first_name'] = '?';
    if( !$item['last_name'] )
      $item['last_name'] = '?';

    $my_contact = new Contact( );
    $my_contact->set_data( $item );
    $item['fk_contact_id'] = $my_contact->write_back( );

    // find organization
    if( $item['org_name'] )
    {
      $item['fk_organization_id'] = find_item( 'name', $item['org_name'], 'organizations' );
      if( !$item['fk_organization_id'] )
      {
        $my_org = new Organization( );

        $org['name'] = $item['org_name'];
        $org['imported'] = 1;

        $my_org->set_data( $org );
        $item['fk_organization_id'] = $my_org->write_back( );
      }
      $my_co = new Contact_org( );
      $my_co->set_data( $item );
      $my_co->write_back( );
    }


    // create interview
    $my_int = new Interview( );
    $my_int->set_data( $item );
    $int_id = $my_int->write_back( );

    $sql = "INSERT INTO interview_projects (fk_project_id, fk_interview_id) VALUES (".$proj_id.", ".$int_id.")";
    mysql_query( $sql );

    $sql = "INSERT INTO user_projects (fk_project_id, fk_user_id) VALUES (".$proj_id.", ".$item['fk_user_id'].")";
    mysql_query( $sql );

    $import_count++;


  }


  $SESS->redirect_msg( 'success', 'Successfully imported '.$import_count.' contacts.' );

}
elseif( $_POST['submit_form'] )
{
  $view_data['err_msg'] = 'There was an error importing your file.';
}

//-------------------------------------------------------------------------------------------
// Sign up Page
//-------------------------------------------------------------------------------------------

$PAGE->title      = "Import";

$PAGE->add_script( 'xmlhttpreq_functions.js' );
$PAGE->add_script( 'forms/signup.js' );
$PAGE->add_script( 'forms/forms.js' );
$PAGE->add_style( 'style_forms.css' );

//===========================================================================================
load_view( 'pagehead' );

?>
<div class="page_content">
 <div class="page_content_full">
<? 
//------------------------------------------------------------ 

load_view( 'admin/import', $view_data );

//------------------------------------------------------------
?>
 </div> 
 
 <div class="spacer" id="page_content_bottom"></div>
</div> <!-- end of page_content -->

<?

//===========================================================================================
load_view( 'pagetail' );
