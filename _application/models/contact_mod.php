<?
//===========================================================================================
// Class:    Contact
//-------------------------------------------------------------------------------------------

// _set_defaults( )
// _post_get_data( $ret_val, $format )
// _post_set_data( $data, $format )
// _delete_data( )
// _load_data( $id )
// _post_load_data( $id )
// _pre_write_back( )
// _write_back( )
// _post_write_back( )


class Contact extends CORE_Model
{
  var $form = NULL;

  var $orgs = array();
  var $deleted_orgs = array();
  
  var $prjs = array();
  var $deleted_prjs = array();

  // ----------------------------------------------------------------------------------------
  function Contact( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'contacts';
    $this->fields = array( 'type', 'is_source', 'first_name', 'last_name', 'salutation', 'degree',
                           'email1', 'email1_type', 'email2', 'email2_type', 'title', 
                           'phone1', 'phone1_type', 'phone2', 'phone2_type', 'phone3', 'phone3_type', 'specialty', 'language', 'area',
                           'reliability', 'recontact', 'background', 'notes',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed', 'imported' );

    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    // set default data
    $this->data['imported'] = 0;
    $this->data['type'] = 1;
    $this->data['is_source'] = 1;
    $this->data['recontact'] = 2;
    $this->data['specialty'] = 1000;
    $this->data['language'] = 1000;
    $this->data['area'] = 1000;
    $this->data['salutation'] = 10;
    $this->data['degree'] = 10;
    $this->data['reliability'] = 10;

    $this->data['email1_type'] = 1;
    $this->data['email2_type'] = 2;

    $this->data['phone1_type'] = 1;
    $this->data['phone2_type'] = 2;
    $this->data['phone3_type'] = 3;

    $this->data['fk_created_by_user'] = $USER->get( 'id' );
    $this->data['created'] = date( 'Y-m-d' ); 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );      
  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;

    $this->form = new CORE_Form( 'f_contact' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'source_id', 'Source ID', NULL, 'hidden' );
    $this->form->add_input( 'first_name', 'First Name', array('maxlength'=>30) );
    $this->form->add_input( 'last_name', 'Last Name', array('maxlength'=>30) );
    $this->form->add_select( 'salutation', $LANG['salutations'], 'Salutation' );

    $this->form->add_select( 'degree', $LANG['degrees'], 'Degree', array( 'multi'=>1, 'me'=>'sel_degree', 'reset'=>'n/a', 'reset_id'=>10  ) );
    $this->form->add_input( 'title', 'Title', array('maxlength'=>200) );

    $this->form->add_select( 'specialty', $LANG['specialties'], 'Therapeutic Area', array( 'multi'=>1, 'me'=>'sel_specialty', 'reset'=>'n/a', 'reset_id'=>1000 ) );
    $this->form->add_select( 'language', $LANG['languages'], 'Language', array( 'multi'=>1, 'me'=>'sel_language', 'reset'=>'n/a', 'reset_id'=>1000 ) );
    $this->form->add_select( 'area', $LANG['areas'], 'Geographical Area', array( 'multi'=>1, 'me'=>'sel_area', 'reset'=>'n/a', 'reset_id'=>1000 ) );

    $this->form->add_select( 'type', $LANG['source_types_full'], 'Source Type' );
    $this->form->add_select( 'is_source', $LANG['contact_types'] );
    $this->form->add_select( 'recontact', $LANG['yes_null'], 'Open to Recontact' );
    $this->form->add_select( 'reliability', $LANG['reliability'], 'Source Reliability' );

    $this->form->add_input( 'email1', 'Email 1', array('maxlength'=>200) );
    $this->form->add_select( 'email1_type', $LANG['email_types']  );
    $this->form->add_input( 'email2', 'Email 2', array('maxlength'=>200) );
    $this->form->add_select( 'email2_type', $LANG['email_types'] );

    $this->form->add_input( 'phone1', 'Phone 1', array('maxlength'=>30) );
    $this->form->add_select( 'phone1_type', $LANG['phone_types'] );
    $this->form->add_input( 'phone2', 'Phone 2', array('maxlength'=>30) );
    $this->form->add_select( 'phone2_type', $LANG['phone_types'] );
    $this->form->add_input( 'phone3', 'Phone 3', array('maxlength'=>30) );
    $this->form->add_select( 'phone3_type', $LANG['phone_types'] );

    $this->form->add_input( 'zipcode', 'Zipcode', array('maxlength'=>5) );
    $this->form->add_textarea( 'background', 'Background' ); 
    $this->form->add_textarea( 'notes', 'Notes' );

    $this->form->add_input( 'created_by', 'Created By' );
    $this->form->add_input( 'changed_by', 'Last Changed By' );

    $this->form->add_input( 'org_search', 'Add Organization', NULL, 'search' );
    $this->form->add_input( 'prj_search', 'Add Project', NULL, 'search' );

    $this->form->add_validation( 'first_name', V_REQUIRED );
    $this->form->add_validation( 'last_name', V_REQUIRED );
    //$this->form->add_validation( 'phone1', V_REGEX, 1, $REGEX['phone'] );
    //$this->form->add_validation( 'phone2', V_REGEX, 1, $REGEX['phone'] );
    //$this->form->add_validation( 'phone3', V_REGEX, 1, $REGEX['phone'] );
    $this->form->add_validation( 'email1', V_REGEX, 1, $REGEX['email'] );
    $this->form->add_validation( 'email2', V_REGEX, 1, $REGEX['email'] );

    // $this->form->create_error_msg( 'phone1', E_INVALID_FORMAT, 'The system does not recognize the format of this phone number.' );
    // $this->form->create_error_msg( 'phone2', E_INVALID_FORMAT, 'The system does not recognize the format of this phone number.' );
    // $this->form->create_error_msg( 'phone3', E_INVALID_FORMAT, 'The system does not recognize the format of this phone number.' );

    $this->form->create_error_msg( 'email1', E_INVALID_FORMAT, 'This does not appear to be a valid email address.' );
    $this->form->create_error_msg( 'email2', E_INVALID_FORMAT, 'This does not appear to be a valid email address.' );

  }

  // ----------------------------------------------
  function _post_set_data( $data, $format )
  {
    $this->orgs = array();
    $this->deleted_orgs = array();

    if( is_array( $data['orgs'] ) )
    {
      foreach( $data['orgs'] as $key => $val )
        $this->create_org( $val, $format );
    }

    if( is_array( $data['deleted_orgs'] ) )
      $this->deleted_orgs = $data['deleted_orgs'];
      
    $this->prjs = array();
    $this->deleted_prjs = array();

    if( is_array( $data['prjs'] ) )
    {
      foreach( $data['prjs'] as $key => $val )
        $this->create_prj( $val, $format );
    }

    if( is_array( $data['deleted_prjs'] ) )
      $this->deleted_prjs = $data['deleted_prjs'];
      
  }

  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    // set changed by data 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    $this->data['phone1'] = CORE_phone( $this->data['phone1'], F_SQL );
    $this->data['phone2'] = CORE_phone( $this->data['phone2'], F_SQL );
    $this->data['phone3'] = CORE_phone( $this->data['phone3'], F_SQL );
  }

  // ----------------------------------------------
  function _post_write_back( )
  {
    $this->write_back_orgs( );
    $this->write_back_prjs( );
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $this->data['phone1'] = CORE_phone( $this->data['phone1'], F_HTM );
    $this->load_orgs( );

    if( is_array( $this->orgs ) )
      foreach( $this->orgs as $org )
        $this->data['orgs'][] = $org->get_data( $format );

    $this->data['primary_org'] = 0;

    if( isset( $this->data['orgs'][0] ) )
      $this->data['primary_org'] = $this->data['orgs'][0]['fk_organization_id'];

    $this->load_prjs( );

    if( is_array( $this->prjs ) )
      foreach( $this->prjs as $prj )
        $this->data['prjs'][] = $prj->get_data( $format );

    $this->data['primary_prj'] = 0;

    if( isset( $this->data['prjs'][0] ) )
      $this->data['primary_prj'] = $this->data['prjs'][0]['fk_prjanization_id'];

    $sql = "SELECT CONCAT_WS(' ', u.first_name, u.last_name ) AS created_by, CONCAT_WS(' ', u2.first_name, u2.last_name) AS changed_by
            FROM user_info AS u, user_info AS u2
            WHERE u.pk_id=".$this->data['fk_created_by_user']." AND u2.pk_id=".$this->data['fk_last_changed_user'];
    $result = mysql_query( $sql );
    if( $res = mysql_fetch_assoc( $result ) )
    {
      $this->data['created_by'] = $res['created_by'];
      $this->data['changed_by'] = $res['changed_by'];
    }
  }

  //=========================================================================================
  // Organizations
  // ----------------------------------------------------------------------------------------
  function load_orgs( )
  {
    if( !$this->id || !$this->valid )
      return false;

    $this->orgs = array( );
    $this->deleted_orgs = array( );

    $sql = "SELECT *
            FROM contact_orgs AS co
              LEFT JOIN organizations AS o ON o.pk_id=co.fk_organization_id 
            WHERE fk_contact_id=".$this->id."
            ORDER BY is_primary DESC, is_current DESC, o.name ASC";

    $result = mysql_query( $sql );

    if(!mysql_num_rows($result))
      return false;
    
    while( $res = mysql_fetch_array($result, MYSQL_ASSOC) )
    {
      $my_org = new Contact_org( array( $this->id, $res['fk_organization_id']) );
      $my_org->load_data( );
      $this->orgs[] = $my_org;
    }

    return true;
  }

  // ----------------------------------------------------------------------------------------
  function create_org( $data, $format=F_PHP )
  {
    $my_org = new Contact_org( );

    $data['fk_contact_id'] = $this->id;

    error_log(print_r($data, True));
    
    $my_org->set_data( $data, $format );

    $this->orgs[] = $my_org;

  }

  // ----------------------------------------------------------------------------------------
  function write_back_orgs( )
  {
    if( !isset( $this->orgs ) )
     return false;

    foreach( $this->orgs as $org )
    {
      $org->set( 'fk_contact_id', $this->id );
      $org->write_back( );
    }

    if( is_array( $this->deleted_orgs ) )
    {
      foreach( $this->deleted_orgs as $val )
      {
        $my_org = new Contact_org( array( $this->id, $val ) );
        $my_org->delete_data( );
      }
    }

    return true;
  }

  //=========================================================================================
  // Projects
  // ----------------------------------------------------------------------------------------
  function load_prjs( )
  {
    if( !$this->id || !$this->valid )
      return false;

    $this->prjs = array( );
    $this->deleted_prjs = array( );

    if ($this->data['is_source']==4){

      $sql = "SELECT *
              FROM contractor_projects AS co
                LEFT JOIN projects AS o ON o.pk_id=co.fk_project_id 
              WHERE fk_contact_id=".$this->id."
              ORDER BY o.name ASC";

      $result = mysql_query( $sql );

      if(!mysql_num_rows($result))
        return false;
      
      while( $res = mysql_fetch_array($result, MYSQL_ASSOC) )
      {
        $my_prj = new Contractor_prj( array( $this->id, $res['fk_project_id']) );
        $my_prj->load_data( );
        $this->prjs[] = $my_prj;
      }

      return true;

    } else {

      $sql = "SELECT *
              FROM contact_projects AS co
                LEFT JOIN projects AS o ON o.pk_id=co.fk_project_id 
              WHERE fk_contact_id=".$this->id."
              ORDER BY o.name ASC";

      $result = mysql_query( $sql );

      if(!mysql_num_rows($result))
        return false;
      
      while( $res = mysql_fetch_array($result, MYSQL_ASSOC) )
      {
        $my_prj = new Contact_prj( array( $this->id, $res['fk_project_id']) );
        $my_prj->load_data( );
        $this->prjs[] = $my_prj;
      }

      return true;

    }
  }

  // ----------------------------------------------------------------------------------------
  function create_prj( $data, $format=F_PHP )
  {
    $my_prj = new Contact_prj( );

    $data['fk_contact_id'] = $this->id;

    $my_prj->set_data( $data, $format );

    $this->prjs[] = $my_prj;

  }

  // ----------------------------------------------------------------------------------------
  function write_back_prjs( )
  {
    if( !isset( $this->prjs ) )
     return false;

    foreach( $this->prjs as $prj )
    {
      $prj->set( 'fk_contact_id', $this->id );
      $prj->write_back( );
    }

    if( is_array( $this->deleted_prjs ) )
    {
      foreach( $this->deleted_prjs as $val )
      {
        $my_prj = new Contact_prj( array( $this->id, $val ) );
        $my_prj->delete_data( );
      }
    }

    return true;
  }
}


//===========================================================================================
// Class:    Contact_orgs
//-------------------------------------------------------------------------------------------

class Contact_org extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Contact_org( $id=array(0, 0) )
  {
    $this->id_name    = array( 'fk_contact_id', 'fk_organization_id' );
    $this->table_name = 'contact_orgs';
    $this->fields = array( 'fk_contact_id', 'fk_organization_id', 'city', 'state', 'country', 'is_primary', 'is_current', 'zipcode' );
    $this->set('id', $id );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    $this->data['is_primary'] = 0;
    $this->data['is_current'] = 1;
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $sql = "SELECT *, pk_id AS id
            FROM organizations
            WHERE pk_id=".$this->data['fk_organization_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['org_name'] = $res['name'];
  }

}

//===========================================================================================
// Class:    Contact_prjs
//-------------------------------------------------------------------------------------------

class Contact_prj extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Contact_prj( $id=array(0, 0) )
  {
    $this->id_name    = array( 'fk_contact_id', 'fk_project_id' );
    $this->table_name = 'contact_projects';
    $this->fields = array( 'fk_contact_id', 'fk_project_id' );
    $this->set('id', $id );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {

  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $sql = "SELECT *, pk_id AS id
            FROM projects
            WHERE pk_id=".$this->data['fk_project_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['prj_name'] = $res['name'];
  }

}

//===========================================================================================
// Class:    Contractor_prjs
//-------------------------------------------------------------------------------------------

class Contractor_prj extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Contractor_prj( $id=array(0, 0) )
  {
    $this->id_name    = array( 'fk_contact_id', 'fk_project_id' );
    $this->table_name = 'contractor_projects';
    $this->fields = array( 'fk_contact_id', 'fk_project_id' );
    $this->set('id', $id );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {

  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $sql = "SELECT *, pk_id AS id
            FROM projects
            WHERE pk_id=".$this->data['fk_project_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['prj_name'] = $res['name'];
  }

}

?>
