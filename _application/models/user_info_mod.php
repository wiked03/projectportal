<?
//===========================================================================================
// Class:    User_info
//-------------------------------------------------------------------------------------------

class User_info extends CORE_Model
{

  // ----------------------------------------------------------------------------------------
  function User_info( $id=0, $create_form=true  )
  {
    $this->id = $id;
    $this->table_name = 'user_info';
    $this->fields = array( 'first_name', 'last_name' );

    if( $create_form )
      $this->create_form( );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    // set default data
    $this->data['temp'] = 0;
    $this->data['imported'] = 0;
    $this->data['level'] = 1;
    $this->data['active'] = 1;
    $this->data['created'] = date( 'Y-m-d' );
  }

  // ----------------------------------------------------------------------------------------
  function _write_back ( )
  {
    if( !$this->valid || !$this->id || !$this->table_name || !$this->id_name || !$this->fields )
      return false;

    // since user can be created without userinfo, we have to search for it
    $sql = "SELECT *
            FROM user_info
            WHERE pk_id=".$this->id;

    $result = mysql_query( $sql );
    
    if( !mysql_num_rows($result) )
    {
      foreach( $this->fields as $field )
        $values[] = $this->get( $field, F_SQL );

      $sql = "INSERT INTO ".$this->table_name."
                  ( pk_id,".implode(',', $this->fields)." )
              VALUES ( ".$this->id.",".implode(',', $values)." )";

      $result = mysql_query( $sql );
      // not needed in this instance
      // $this->id = mysql_insert_id( );
    }
    else
    {  
      foreach( $this->fields as $field )
        $values[] = $field.'='.$this->get( $field, F_SQL );

      $sql = "UPDATE ".$this->table_name."
              SET ".implode(',', $values)."
              WHERE ".$this->id_name."=".$this->id;

      $result = mysql_query( $sql );
    }

    return $this->id;
  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;

    $this->form = new CORE_Form( 'f_contact' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'first_name', 'First Name', array('maxlength'=>30) );
    $this->form->add_input( 'last_name', 'Last Name', array('maxlength'=>30) );
    $this->form->add_select( 'level', $LANG['user_types'], 'User Level' );
    $this->form->add_select( 'active', $LANG['yes'], 'Active' );

    $this->form->add_input( 'email', 'Email', array('maxlength'=>200) );
    $this->form->add_input( 'username', 'Username', array('maxlength'=>32) );


    $this->form->add_validation( 'username', V_REQUIRED );
    $this->form->add_validation( 'first_name', V_REQUIRED );
    $this->form->add_validation( 'last_name', V_REQUIRED );
    $this->form->add_validation( 'email', V_REQUIRED );

    $this->form->add_validation( 'username', V_REGEX, 0, '/[a-z][-_a-z0-9]{2,31}/i' );
    $this->form->add_validation( 'email', V_REGEX, 1, $REGEX['email'] );

    $this->form->create_error_msg( 'username', E_INVALID_FORMAT, 'Username may contain only letters, numbers, dashes, and underscores and must be 3 letters long.' );

  }
  
  function full_name(){
  	return $this->get('first_name') . ' ' . $this->get('last_name'); 
  	
  }

}



//===========================================================================================
// Class:    User_project
//-------------------------------------------------------------------------------------------

class User_project extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function User_project( $id=array(0, 0) )
  {
    $this->id_name    = array( 'fk_user_id', 'fk_project_id' );
    $this->table_name = 'user_projects';
    $this->fields = array( 'fk_user_id', 'fk_project_id' );
    $this->set('id', $id );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    return;
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $sql = "SELECT CONCAT_WS(' ', first_name, last_name) AS name
            FROM user_info
            WHERE pk_id=".$this->data['fk_user_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['user_name'] = $res['name'];

    // ---------------------
    $sql = "SELECT name
            FROM projects
            WHERE pk_id=".$this->data['fk_project_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['proj_name'] = $res['name'];
  }

}


//===========================================================================================
// Class:    Contractor_project
//-------------------------------------------------------------------------------------------

class Contractor_project extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Contractor_project( $id=array(0, 0) )
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
    return;
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $sql = "SELECT CONCAT_WS(' ', first_name, last_name) AS name
            FROM contacts
            WHERE pk_id=".$this->data['fk_contact_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['user_name'] = $res['name'];

    // ---------------------
    $sql = "SELECT name
            FROM projects
            WHERE pk_id=".$this->data['fk_project_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['proj_name'] = $res['name'];
  }

}


?>
