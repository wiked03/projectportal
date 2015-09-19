<?
//===========================================================================================
// Class:    Userlog
//-------------------------------------------------------------------------------------------

class Userlog extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Userlog( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'userlogs';
    $this->fields = array( 'name', 'description', 'fk_created_by_user', 'created');
    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->data['fk_created_by_user'] = $USER->get( 'id' );
    $this->data['created'] = date( 'Y-m-d H:i:s' );
  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;

    $my_view = new View( );


    $this->form = new CORE_Form( 'f_userlog' );

    $this->form->data = &$this->data;

    $this->form->add_select( 'name', $LANG['log_type'], 'Type', array( 'multi'=>1, 'reset'=>'- any -', 'reset_id'=>0, 'me'=>'sel_log_name' ) );
    //$this->form->add_input( 'name', 'Log Type', array('maxlength'=>255) );
    //$this->form->add_input( 'description', 'Description', array('maxlength'=>255) );
    $this->form->add_select( 'created_by', $my_view->get_list( 'active_users', F_HTM, $this->get('created_by') ), 'Created By', array( 'me'=>'sel_created_by', 'reset'=>'None Selected', 'multi'=>1 ) );
    $this->form->add_input( 'start', 'Start Date', NULL, 'date'  );
    $this->form->add_input( 'end', 'End Date', NULL, 'date'  );
  }


  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {

  }

  // ----------------------------------------------------------------------------------------
  function _post_write_back( )
  {

  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    
  }
 
}

?>
