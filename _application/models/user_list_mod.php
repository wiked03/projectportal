<?
//===========================================================================================
// Class:    User_List
//-------------------------------------------------------------------------------------------

class User_List extends CORE_Model
{

  // ----------------------------------------------------------------------------------------
  function User_List( $id=0, $create_form=true  )
  {
    $this->id = $id;
    $this->table_name = 'user_info';
    $this->fields = array( 'users' );

    if( $create_form )
      $this->create_form( );

  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;
    
    $users = Array();
    $sql = "SELECT pk_id, CONCAT_WS(' ', first_name, last_name) AS name
            FROM user_info ORDER BY CONCAT_WS(' ', first_name, last_name)";
    $result = mysql_query( $sql );
    
    while ( $res = mysql_fetch_assoc( $result ) ){
			$users[$res['pk_id']] = $res['name'];      	
    }

    $this->form = new CORE_Form( 'f_contact' );

    $this->form->data = &$this->data;

    $this->form->add_select( 'users', $users, 'Select User' );
  }

}

?>
