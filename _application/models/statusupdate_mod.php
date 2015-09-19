<?
//===========================================================================================
// Class:    StatusUpdate
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

class StatusUpdate extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function StatusUpdate( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'statusupdates';
    $this->fields = array( 'fk_user_id', 'fk_contractor_id', 'fk_project_id', 'int_start_date', 'notes', 'status', 'resolution', 'concern',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed', 'resolved_date');
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
    $this->data['status'] = 0;
    $this->data['concern'] = 0;
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
  
    // Moved to edit_StatusUpdates.php
    //$this->form->add_select( 'fk_user_id', $my_view->get_list( 'project_users', F_HTM, $this->get('fk_project_id') ), 'Contractor' );
    $this->form->add_input( 'int_start_date', 'Date', NULL, 'date');
    $this->form->add_textarea( 'notes', 'Notes' );
    $this->form->add_select( 'status', $LANG['status'], 'Resolved' );
    $this->form->add_select( 'concern', $LANG['concerns'], 'Status' );
    $this->form->add_textarea( 'resolution', 'Resolution' );
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

    //error_log(print_r($this->data, true));
    
    // set changed by data 
    /*
    if(!($this->data['status'])){
      $this->data['status'] = 0;
    }*/

    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    
    //error_log(print_r($this->data, true));
    if ($this->data['status'] != '1'){
    	if(!($this->data['int_start_date'])){
    		$this->set( 'int_start_date', date( 'Y-m-d' ) );
    		$this->data['int_start_date']   = CORE_date( $this->data['int_start_date'], F_DATE_SQL );
    	}
    } else {
    	$this->set( 'resolved_date', date( 'Y-m-d' ) );
    	$this->data['resolved_date']   = CORE_date( $this->data['resolved_date'], F_DATE_SQL );
    }

  }

}

?>
