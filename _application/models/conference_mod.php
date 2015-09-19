<?
//===========================================================================================
// Class:    Conference
//-------------------------------------------------------------------------------------------

class Conference extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Conference( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'conferences';
    $this->fields = array( 'name', 'conference_date', 'active', 'conference_end_date', 'location' );

    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    // set default data
  	$this->set( 'int_date', date( 'Y-m-d' ) );
  	$this->set( 'int_end_date', date( 'Y-m-d' ) );  	
  	$this->data['active'] = 1;
  }
  
  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
  	global $LANG, $REGEX;
  	  
  	$this->form = new CORE_Form( 'f_conference' );
  
  	$this->form->data = &$this->data;
  
  	$this->form->add_input( 'name', 'Name', array('maxlength'=>255) );  	
  	$this->form->add_input( 'conference_date', 'Start Date', NULL, 'date'  );
  	$this->form->add_input( 'conference_end_date', 'End Date', NULL, 'date'  );
  	$this->form->add_input( 'location', 'Location' );
  	$this->form->add_select( 'active', $LANG['yes'], 'Active' );  
  }
  
  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    // set changed by data 
    $this->data['conference_date']   = CORE_date( $this->data['conference_date'], F_DATE_SQL );
    $this->data['conference_end_date']   = CORE_date( $this->data['conference_end_date'], F_DATE_SQL );
  }
  
  // ----------------------------------------------------------------------------------------
  function _post_write_back( )
  {
  	$this->write_back_users();
  }
  
  // ----------------------------------------------
  function _post_load_data( $id )
  {
  	$this->load_users( );  
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
    $found_conference = $this->get_conference_by_name( $this->get('name') );

    if( !$this->get('name') )
      $error['name'] = E_ENTRY_REQUIRED;
    elseif( $found_conference && ($found_conference != $this->id) )
      $error['name'] = E_VALUE_NOT_UNIQUE;

    return $error;
  }

  // ----------------------------------------------------------------------------------------
  function get_conference_by_name ( $name )
  {
    $result = mysql_query("SELECT *
                          FROM ".$this->table_name."
                          WHERE name=".CORE_encode( $name, F_SQL ) );
    if(!mysql_num_rows($result))
      return false;
    
    $result = mysql_fetch_array($result, MYSQL_ASSOC);

    return $result[$this->id_name];
  }
  
  //=========================================================================================
  // User/Conferences
  // ----------------------------------------------------------------------------------------
  function load_users( )
  {
  	$sql = "SELECT *, CONCAT_WS(' ', first_name, last_name) AS user_name
            FROM user_conferences AS up
              LEFT JOIN user_info AS ui ON ui.pk_id=up.fk_user_id
            WHERE fk_conference_id=".$this->id;
  
  	$result = mysql_query( $sql );
  
  	$ret_val = array( 'id'=>array(), 'name'=>array() );
  
  	while( $res = mysql_fetch_assoc( $result ) )
  	{
  		$ret_val['id'][] = $res['fk_user_id'];
  		$ret_val['name'][] = $res['user_name'];
  
  	}
  
  	$this->set( 'attendees', implode( '.', $ret_val['id'] ), F_PHP );
  	$this->set( 'user_names', $ret_val['name'], F_PHP );
  }
  
  // ----------------------------------------------------------------------------------------
  function write_back_users( )
  {
  	mysql_query( "DELETE FROM user_conferences WHERE fk_conference_id=".$this->id );
  
  	$user_list = $this->get( 'attendees', F_PHP );
  
  	if( is_string( $user_list ) )
  		$user_list = explode( '.', $user_list );
  
  	if( $user_list )
  		foreach( $user_list as $user_id )
  		mysql_query( "INSERT INTO user_conferences ( fk_conference_id, fk_user_id ) VALUES ( ".$this->id.", ".$user_id." )" );
  }
 
}

?>
