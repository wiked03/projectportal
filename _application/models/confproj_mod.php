<?
//===========================================================================================
// Class:    Confproj
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

load_model( 'conference' );
load_model( 'project' );

class Confproj extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Confproj( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'conf_projs';
    $this->fields = array( 'fk_conference_id', 'fk_project_id', 'int_amount',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed',
    					   'num_days');
    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->set( 'int_amount', 0 );
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
  
    // Moved to edit_confprojs.php
    //$this->form->add_select( 'fk_conference_id', $my_view->get_list( 'project_conferences', F_HTM, $this->get('fk_project_id') ), 'Conference' );
    $this->form->add_input( 'int_amount', 'Amount', NULL, 'text');
    $this->form->add_input( 'num_days', 'Number of Days' );
    $this->form->add_select( 'attendees', $my_view->get_list( 'project_users', F_HTM, $this->get('id') ), 'Attendees', array( 'me'=>'sel_users', 'reset'=>'None Selected', 'multi'=>1 ) );
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $my_view = new View( );

    $my_conference = new Conference( $this->get( 'fk_conference_id' ) );
    $my_conference->load_data( );

    $this->data['conference'] = $my_view->get( 'conference', $this->get( 'fk_conference_id' ) );

    $my_project = new Project( $this->get( 'fk_project_id' ) );
    $my_project->load_data( );

    $this->data['project'] = $my_view->get( 'project', $this->get( 'fk_project_id' ) );

    $this->load_users( );   
  }

  // ----------------------------------------------
  function _post_set_data(  $data, $format  )
  {
    $my_view = new View( );
    
    $my_conference = new Conference( $this->get( 'fk_conference_id' ) );
    $my_conference->load_data( );

    $this->data['conference'] = $my_view->get( 'conference', $this->get( 'fk_conference_id' ) );
    $my_project = new Project( $this->get( 'fk_project_id' ) );
    $my_project->load_data( );


    $this->data['project'] = $my_view->get( 'project', $this->get( 'fk_project_id' ) );

  }

  function _post_write_back( )
  {
  	$this->write_back_users();
  }
    
  
  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    // set changed by data 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    $this->data['int_date']   = CORE_date( $this->data['int_date'], F_DATE_SQL );
  }

  //=========================================================================================
  // User/Conf_projs
  // ----------------------------------------------------------------------------------------
  function load_users( )
  {
  	$sql = "SELECT *, CONCAT_WS(' ', first_name, last_name) AS user_name
            FROM user_conf_projs AS up
              LEFT JOIN user_info AS ui ON ui.pk_id=up.fk_user_id
            WHERE fk_conf_proj_id=".$this->id;
  
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
  	mysql_query( "DELETE FROM user_conf_projs WHERE fk_conf_proj_id=".$this->id );
  
  	$user_list = $this->get( 'attendees', F_PHP );
  
  	if( is_string( $user_list ) )
  		$user_list = explode( '.', $user_list );
  
  	if( $user_list )
  		foreach( $user_list as $user_id )
  		mysql_query( "INSERT INTO user_conf_projs ( fk_conf_proj_id, fk_user_id ) VALUES ( ".$this->id.", ".$user_id." )" );
  }
  
}

?>
