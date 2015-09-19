<?
//===========================================================================================
// Class:    Deliverable
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

load_model( 'project' );

class Deliverable extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Deliverable( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'deliverables';
    $this->fields = array( 'fk_project_id', 'int_start_date', 'notes', 'clientinteraction', 'type',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed');
    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->set( 'int_start_date', date( 'Y-m-d' ) );
    $this->data['fk_created_by_user'] = $USER->get( 'id' );
    $this->data['clientinteraction'] = 0;
    $this->data['type'] = 0;
    $this->data['created'] = date( 'Y-m-d' ); 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
  }

  // ----------------------------------------------------------------------------------------
  function create_form( )
  {
    global $LANG, $REGEX;

    $my_view = new View( );

    $this->form = new CORE_Form( 'f_exp' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'fk_project_id', '', NULL, 'hidden' );
    $this->form->add_input( 'project', 'Project' );
    $this->form->add_input( 'int_start_date', 'Date', NULL, 'date');
    $this->form->add_textarea( 'notes', 'Notes' );
    $this->form->add_select( 'clientinteraction', $LANG['clientinteraction'], 'Client Interaction' );
    $this->form->add_select( 'type', $LANG['deliverable_type'], 'Type' );
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $my_view = new View( );

    $my_user = new User( $this->get( 'fk_user_id' ) );
    $my_user->load_data( );

    $this->data['user'] = $my_view->get( 'user', $this->get( 'fk_user_id' ) );

    $my_project = new Project( $this->get( 'fk_project_id' ) );
    $my_project->load_data( );

    $this->data['project'] = $my_view->get( 'project', $this->get( 'fk_project_id' ) );

  }

  // ----------------------------------------------
  function _post_set_data(  $data, $format  )
  {
    $my_view = new View( );
    
    $my_user = new User( $this->get( 'fk_user_id' ) );
    $my_user->load_data( );

    $this->data['user'] = $my_view->get( 'user', $this->get( 'fk_user_id' ) );
    $my_project = new Project( $this->get( 'fk_project_id' ) );
    $my_project->load_data( );


    $this->data['project'] = $my_view->get( 'project', $this->get( 'fk_project_id' ) );

  }

  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    $this->data['int_start_date']   = CORE_date( $this->data['int_start_date'], F_DATE_SQL );
  }

}

?>
