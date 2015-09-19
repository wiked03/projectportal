<?
//===========================================================================================
// Class:    Resource
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

load_model( 'contact' );
load_model( 'project' );

class Resource extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Resource( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'resources';
    $this->fields = array( 'fk_user_id', 'fk_project_id', 'effort', 'int_start_date', 'int_end_date', 'notes',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed', 'fk_contractor_id');
    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->set( 'int_start_date', date( 'Y-m-d' ) );
    $this->set( 'int_end_date', date( 'Y-m-d' ) );
    $this->set( 'effort', 0 );
    $this->data['fk_created_by_user'] = $USER->get( 'id' );
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
  
    // Moved to edit_resources.php
    //$this->form->add_select( 'fk_user_id', $my_view->get_list( 'project_users', F_HTM, $this->get('fk_project_id') ), 'Contractor' );
    $this->form->add_input( 'effort', '% Effort', NULL, 'text');
    $this->form->add_input( 'int_start_date', 'Start Date', NULL, 'date');
    $this->form->add_input( 'int_end_date', 'End Date', NULL, 'date');
    $this->form->add_textarea( 'notes', 'Notes' );
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

    // set changed by data 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    $this->data['int_start_date']   = CORE_date( $this->data['int_start_date'], F_DATE_SQL );
    $this->data['int_end_date']   = CORE_date( $this->data['int_end_date'], F_DATE_SQL );
  }

}

?>
