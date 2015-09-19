<?
//===========================================================================================
// Class:    Contractor
//-------------------------------------------------------------------------------------------

class Contractor extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Contractor( $id=0, $create_form=true  )
  {
    $this->id = $id;
    $this->table_name = 'contractors';
    $this->fields = array( 'name', 'email', 'created', 'active' );

    if( $create_form )
      $this->create_form( );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    // set default data
    $this->data['active'] = 1;
    $this->data['created'] = date( 'Y-m-d' );
  }

  // ----------------------------------------------------------------------------------------
  function _post_set_data( $data, $format )
  {

  }

  // ----------------------------------------------------------------------------------------
  // E_OK
  // E_ENTRY_REQUIRED
  // E_INVALID_FORMAT
  // E_SELECTION_REQUIRED
  // E_INVALID_DATE
  // E_OUT_OF_RANGE
  // E_VALUE_NOT_UNIQUE
  // E_USER_NO_EMAIL
  // E_USER_NO_USERNAME
  // E_USER_WRONG_PASSWORD
  function validate ( )
  {
    $found_user = $this->get_contractor_by_name( $this->get('name') );

    if( !$this->get('name') )
      $error['name'] = E_ENTRY_REQUIRED;
    elseif( !preg_match( '/^[A-Z0-9._-]+$/i', $this->get('name') ) )
      $error['name'] = E_INVALID_FORMAT;
    elseif( $found_user && ($found_user != $this->id) )
      $error['name'] = E_VALUE_NOT_UNIQUE;

    return $error;
  }

  // ----------------------------------------------------------------------------------------
  function get_contractor_by_name ( $name )
  {
    $result = mysql_query("SELECT *
                          FROM ".$this->table_name."
                          WHERE name=".CORE_encode( $name, F_SQL ) );
    if(!mysql_num_rows($result))
      return false;
    
    $result = mysql_fetch_array($result, MYSQL_ASSOC);

    return $result[$this->id_name];
  }

  // ----------------------------------------------------------------------------------------
  function get_contractor_by_email( $email )
  {
    $sql = "SELECT *
            FROM ".$this->table_name."
            WHERE email=".CORE_encode( $email, F_SQL );

    $result = mysql_query( $sql );
    if(!mysql_num_rows($result))
      return false;
    
    $result = mysql_fetch_assoc( $result );

    return $result['pk_id'];
  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;

    $this->form = new CORE_Form( 'f_contact' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'name', 'Name', array('maxlength'=>100) );
    $this->form->add_input( 'email', 'Email', array('maxlength'=>100) );
    $this->form->add_select( 'active', $LANG['yes'], 'Active' );

    $this->form->add_validation( 'name', V_REQUIRED );
    //$this->form->add_validation( 'email', V_REQUIRED );
    $this->form->add_validation( 'name', V_REGEX, 0, '/[a-z][-_a-z0-9]{2,31}/i' );
    $this->form->add_validation( 'email', V_REGEX, 1, $REGEX['email'] );

  }

}

?>
