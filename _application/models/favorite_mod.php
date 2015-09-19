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


class Favorite extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Favorite( $id=0 )
  {
    $this->id = $id;
    $this->table_name = 'favorites';
    $this->fields = array( 'fk_user_id', 'link', 'title', 'type' );

    $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->data['fk_user_id'] = $USER->get( 'id' );
    $this->data['type'] = 0;
  }

}


?>
