<?
//===========================================================================================
// Class:    Interview
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

class Interview extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Interview( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'interviews';

    $this->fields = array( 'fk_user_id', 'fk_contact_id', 'int_number', 'int_date', 'approach', 'method', 'credibility', 'confidential', 'int_background', 
                           'int_notes', 'rate', 'paid',
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 'last_changed', 'imported', 'is_activity' );

    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

   	// Make sure activity/interview date is always EST
    date_default_timezone_set('America/New_York');    
    $this->set( 'int_date', date( 'Y-m-d' ) );
    $this->set( 'int_number', 0 );
    $this->set( 'credibility', 3 );
    $this->set( 'approach', 13 );
    $this->set( 'method', 1 );
    $this->set( 'paid', 2 );

    $this->data['imported'] = 0;
    $this->data['is_activity'] = 0;
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

    $this->form = new CORE_Form( 'f_int' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'fk_user_id',    '', NULL, 'hidden' );
    $this->form->add_input( 'analyst', 'Primary Research Specialist' );
    $this->form->add_input( 'fk_contact_id', '', NULL, 'hidden' );
    $this->form->add_input( 'contact', 'Source' );

    $projects = $my_view->get_list( 'all_active_projects', F_HTM, $this->id );
    $conferences = $my_view->get_list( 'all_active_conferences', F_HTM, $this->id );
    
    $this->form->add_select( 'projects', $projects, 'Projects', array( 'me'=>'sel_projects', 'reset'=>'No Projects selected', 'multi'=>1 ) ); 
    $this->form->add_select( 'conferences', $conferences, 'Conferences', array( 'me'=>'sel_conferences', 'reset'=>'No Conferences selected', 'multi'=>1 ) );
    $this->form->add_select( 'credibility', $LANG['credibility'], 'Credibility' ); 
    $this->form->add_select( 'approach', $LANG['approach'], 'Approach' ); 
    $this->form->add_select( 'method', $LANG['contact_method'], 'Method of Contact' );
    $this->form->add_input(  'rate', 'Rate or Amount' ); 
    $this->form->add_select( 'paid', $LANG['yes_null'], 'Honoraria Paid' ); 

    $this->form->add_input( 'int_date', 'Interview Date', NULL, 'date'  );

    $this->form->add_input( 'org_search', 'Organization', NULL, 'search' );

    $this->form->add_textarea( 'confidential', 'Interview and Key Takeaways' );
    $this->form->add_textarea( 'int_background', 'Relevant Interview Background' );

    // Now these fields are combined into "Interview and Key Takeaways" (formely confidential)
    //$this->form->add_textarea( 'source_comments', 'Source Comments' );
    //$this->form->add_textarea( 'analyst_comments', 'Analyst Comments' );
    $this->form->add_textarea( 'int_notes', 'PRS Notes' );
    
    // source
    $this->form->add_input( 'first_name', 'First Name', array('maxlength'=>30) );
    $this->form->add_input( 'last_name', 'Last Name', array('maxlength'=>30) );
    $this->form->add_select( 'salutation', $LANG['salutations'], 'Salutation' );
    //$this->form->add_select( 'degree', $LANG['degrees'], 'Degree' );    
    $this->form->add_select( 'degree', $LANG['degrees'], 'Degree', array( 'multi'=>1, 'me'=>'sel_degree', 'reset'=>'n/a', 'reset_id'=>10  ) );
    $this->form->add_input( 'title', 'Title', array('maxlength'=>200) );

  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
    $my_view = new View( );

    $my_contact = new Contact( $this->get( 'fk_contact_id' ) );
    $my_contact->load_data( );

    $this->data['analyst'] = $my_view->get( 'user', $this->get( 'fk_user_id' ) );
    $this->data['contact'] = $my_view->get( 'contact', $this->get( 'fk_contact_id' ) );
    $this->data['contact_org'] = $my_contact->get( 'primary_org' );
    $this->data['org_name']  = $my_view->get( 'org', $my_contact->get( 'primary_org' ) );
    
    $this->data['first_name'] = $my_contact->get( 'first_name' );
    $this->data['last_name'] = $my_contact->get( 'last_name' );
    $this->data['degree'] = $my_contact->get( 'degree' );
    $this->data['title'] = $my_contact->get( 'title' );
    $this->data['is_source'] = $my_contact->get( 'is_source' );

    $this->load_projects( );
    $this->load_conferences( );
  }

  // ----------------------------------------------
  function _post_set_data(  $data, $format  )
  {
    $my_view = new View( );
    
    $my_contact = new Contact( $this->get( 'fk_contact_id' ) );
    $my_contact->load_data( );

    $this->data['analyst'] = $my_view->get( 'user', $this->get( 'fk_user_id' ) );
    $this->data['contact'] = $my_view->get( 'contact', $this->get( 'fk_contact_id' ) );
    
    $this->data['first_name'] = $my_contact->get( 'first_name' );
    $this->data['last_name'] = $my_contact->get( 'last_name' );
    $this->data['degree'] = $my_contact->get( 'degree' );
    $this->data['title'] = $my_contact->get( 'title' );
    $this->data['is_source'] = $my_contact->get( 'is_source' );    
  }

  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    // set changed by data 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    $this->data['int_date']   = CORE_date( $this->data['int_date'], F_DATE_SQL );

    if( !$this->get( 'int_number' ) )
    {
      $sql = "SELECT COUNT(pk_id) AS int_count FROM interviews WHERE fk_contact_id=".$this->get( 'fk_contact_id' );
      $result = mysql_query( $sql );
      $res = mysql_fetch_assoc( $result );
      $this->set( 'int_number', ($res['int_count'] + 1) );
    }
  }

  // ----------------------------------------------------------------------------------------
  function _post_write_back( )
  {
    $this->write_back_projects();
    $this->write_back_conferences();
  }

  //=========================================================================================
  // Interview/Projects
  // ----------------------------------------------------------------------------------------
  function load_projects( )
  {
    $sql = "SELECT ip.fk_project_id, p.name AS project_name
            FROM interview_projects AS ip
              LEFT JOIN projects AS p ON p.pk_id=ip.fk_project_id
            WHERE fk_interview_id=".$this->id;

    $result = mysql_query( $sql );

    $ret_val = array( 'id'=>array(), 'name'=>array() );
    
    while( $res = mysql_fetch_assoc( $result ) )
    {
      $ret_val['id'][] = $res['fk_project_id'];
      $ret_val['name'][] = $res['project_name'];
 
    }

    $this->set( 'projects', implode( '.', $ret_val['id'] ), F_PHP );
    $this->set( 'project_names', $ret_val['name'], F_PHP );
  }

  // ----------------------------------------------------------------------------------------
  function write_back_projects( )
  {
    mysql_query( "DELETE FROM interview_projects WHERE fk_interview_id=".$this->id );

    $proj_list = $this->get( 'projects', F_PHP );

    if( is_string( $proj_list ) )
      $proj_list = explode( '.', $proj_list );

    if( $proj_list )
      foreach( $proj_list as $proj_id )
        mysql_query( "INSERT INTO interview_projects ( fk_interview_id, fk_project_id ) VALUES ( ".$this->id.", ".$proj_id." )" );
  }
  
  //=========================================================================================
  // Interview/Conferences
  // ----------------------------------------------------------------------------------------
  function load_conferences( )
  {
  	$sql = "SELECT ip.fk_conference_id, p.name AS conference_name
            FROM interview_conferences AS ip
              LEFT JOIN conferences AS p ON p.pk_id=ip.fk_conference_id
            WHERE fk_interview_id=".$this->id;
  
  	$result = mysql_query( $sql );
  
  	$ret_val = array( 'id'=>array(), 'name'=>array() );
  
  	while( $res = mysql_fetch_assoc( $result ) )
  	{
  		$ret_val['id'][] = $res['fk_conference_id'];
  		$ret_val['name'][] = $res['conference_name'];
  
  	}
  
  	$this->set( 'conferences', implode( '.', $ret_val['id'] ), F_PHP );
  	$this->set( 'conference_names', $ret_val['name'], F_PHP );
  }
  
  // ----------------------------------------------------------------------------------------
  function write_back_conferences( )
  {
  	mysql_query( "DELETE FROM interview_conferences WHERE fk_interview_id=".$this->id );
  
  	$proj_list = $this->get( 'conferences', F_PHP );
  
  	if( is_string( $proj_list ) )
  		$proj_list = explode( '.', $proj_list );
  
  	if( $proj_list )
  		foreach( $proj_list as $proj_id )
  		mysql_query( "INSERT INTO interview_conferences ( fk_interview_id, fk_conference_id ) VALUES ( ".$this->id.", ".$proj_id." )" );
  }

}


//===========================================================================================
// Class:    Interview_project
//-------------------------------------------------------------------------------------------

class Interview_project extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Interview_project( $id=array(0, 0) )
  {
    $this->id_name    = array( 'fk_interview_id', 'fk_project_id' );
    $this->table_name = 'interview_projects';
    $this->fields = array( 'fk_interview_id', 'fk_project_id' );
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
    $sql = "SELECT name
            FROM projects
            WHERE pk_id=".$this->data['fk_project_id'];

    $result = mysql_query( $sql );

    if( $res = mysql_fetch_assoc( $result ) )
      $this->data['proj_name'] = $res['name'];
  }

}

//===========================================================================================
// Class:    Interview_conference
//-------------------------------------------------------------------------------------------

class Interview_conference extends CORE_Model
{
	// ----------------------------------------------------------------------------------------
	function Interview_conference( $id=array(0, 0) )
	{
		$this->id_name    = array( 'fk_interview_id', 'fk_conference_id' );
		$this->table_name = 'interview_conferences';
		$this->fields = array( 'fk_interview_id', 'fk_conference_id' );
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
		$sql = "SELECT name
            FROM conferences
            WHERE pk_id=".$this->data['fk_conference_id'];

		$result = mysql_query( $sql );

		if( $res = mysql_fetch_assoc( $result ) )
			$this->data['proj_name'] = $res['name'];
	}

}

?>
