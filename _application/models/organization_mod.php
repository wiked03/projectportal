<?
//===========================================================================================
// Class:    Organization
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


class Organization extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Organization( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'organizations';
    $this->fields = array( 'name', 'notes', 'address', 'city', 'state', 'zipcode',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed', 'imported' );

    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->data['imported'] = 0;
    $this->data['fk_created_by_user'] = $USER->get( 'id' );
    $this->data['created'] = date( 'Y-m-d' ); 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );    
  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;

    $this->form = new CORE_Form( 'f_org' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'name', 'Name', array('maxlength'=>250) );
    $this->form->add_input( 'address', 'Address', array('maxlength'=>250) );
    $this->form->add_input( 'city', 'City', array('maxlength'=>60) );
    $this->form->add_select( 'state', $LANG['states_short'], State );
    
    $this->form->add_input( 'zipcode', 'Zipcode', array('maxlength'=>5) );
    
    $this->form->add_textarea( 'notes', 'Notes' );

    $this->form->add_validation( 'name', V_REQUIRED );

    $this->form->create_error_msg( 'name', E_VALUE_NOT_UNIQUE, 'This organization name is already in use.' );

  }

  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    // set changed by data 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
  }

  // ----------------------------------------------------------------------------------------
  function check_for_duplicate( $write_back=false )
  {
    $sql = "SELECT pk_id
            FROM organizations
            WHERE name=".$this->get( 'name', F_SQL );

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      return $this->load_data( $res['pk_id'] );
    elseif( $write_back )
      return $this->write_back( );
    else
      return false;
  }

}


?>
