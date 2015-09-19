<?



class Project extends CORE_Model
{
  // ----------------------------------------------------------------------------------------
  function Project( $id=0, $create_form=true )
  {
    $this->id = $id;
    $this->table_name = 'projects';
    $this->fields = array( 'fk_client_id', 'name', 'description', 'start', 
                           'end', 'is_active', 'fk_poc_id', 'fk_pm_id', 'notes', 
                           'fk_created_by_user', 'created', 'fk_last_changed_user', 
                           'last_changed', 'imported', 'value', 'hourly_rate', 
                           'specialty', 'poc', 'fk_target_id', 'is_life_science',
                           'industry', 'fk_dir_id', 'prefix', 'bd_poc', 'months');

    $this->_set_defaults( );

    if( $create_form )
      $this->create_form( );
  }

  // ----------------------------------------------------------------------------------------
  function _set_defaults( )
  {
    global $USER;

    $this->data['value'] = 0;
    $this->data['hourly_rate'] = 0;
    $this->data['months'] = 1;
    $this->data['poc'] = '';
    $this->data['imported'] = 0;
    $this->data['is_active'] = 1;
    $this->data['is_life_science'] = 1;
    $this->data['prefix'] = 0;
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


    $this->form = new CORE_Form( 'f_project' );

    $this->form->data = &$this->data;

    $this->form->add_input( 'name', 'Project Name', array('maxlength'=>255) );

    //$this->form->add_input( 'description', 'Description', array('maxlength'=>255) );
    $this->form->add_textarea( 'description', 'Description' );

    $this->form->add_select( 'is_active', $LANG['open'], 'Status' );
    $this->form->add_select( 'is_life_science', $LANG['life_science'], 'PP Designation' );
    $this->form->add_select( 'prefix', $LANG['prefix'], 'Contract Origination' );
    
    $this->form->add_input( 'id_prefix', 'Project ID' );
    
    $this->form->add_select( 'fk_pm_id', $my_view->get_list( 'active_users_with_none', F_HTM, $this->get('fk_pm_id') ), 'Lead Analyst' );
    $this->form->add_select( 'fk_dir_id', $my_view->get_list( 'active_users_with_none', F_HTM, $this->get('fk_dir_id') ), 'Director' );

    $this->form->add_select( 'directors', $my_view->get_list( 'active_users', F_HTM, $this->get('fk_dir_id') ), 'Directors', array( 'me'=>'sel_directors', 'reset'=>'None Selected', 'multi'=>1 ) );
    $this->form->add_select( 'managers', $my_view->get_list( 'active_users', F_HTM, $this->get('fk_pm_id') ), 'Lead Analysts', array( 'me'=>'sel_managers', 'reset'=>'None Selected', 'multi'=>1 ) );
    $this->form->add_select( 'analysts', $my_view->get_list( 'project_users', F_HTM, $this->get('id') ), 'Analysts', array( 'me'=>'sel_users', 'reset'=>'None Selected', 'multi'=>1 ) );
    $this->form->add_select( 'conferences', $my_view->get_list( 'project_conferences', F_HTM, $this->get('id') ), 'Conferences', array( 'me'=>'sel_conferences', 'reset'=>'None Selected', 'multi'=>1 ) );
    
    $this->form->add_select( 'collectors', $my_view->get_list( 'project_users', F_HTM, $this->get('id') ), 'Primary Research Specialists', array( 'me'=>'sel_collectors', 'reset'=>'None Selected', 'multi'=>1 ) );
    $this->form->add_select( 'contractors', $my_view->get_list( 'all_contractors', F_HTM, $this->get('id') ), 'Contractors', array( 'me'=>'sel_contractors', 'reset'=>'None Selected', 'multi'=>1 ) ); 
    //$this->form->add_select( 'points_of_contact', $my_view->get_list( 'all_contacts', F_HTM, $this->get('id') ), 'Points of Contacts', array( 'me'=>'sel_points_of_contact', 'reset'=>'None Selected', 'multi'=>1 ) ); 
    $this->form->add_input( 'poc', 'Point of Contact', array('maxlength'=>255) );
    $this->form->add_select( 'specialty', $LANG['specialties'], 'Therapeutic Area', array( 'multi'=>1, 'me'=>'sel_specialty', 'reset'=>'n/a', 'reset_id'=>1000 ) );
    $this->form->add_select( 'industry', $LANG['industries'], 'Industry', array( 'multi'=>1, 'me'=>'sel_industry', 'reset'=>'n/a', 'reset_id'=>1000 ) );

    $this->form->add_input( 'value', 'Research Budget', array('maxlength'=>15, 'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'sum_expenses', 'Project Expenses', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'sum_conferences_value', 'Conference Budget', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'total_value', 'Total Budget', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'profit', 'Project Balance', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'pct_spent', '% Spent', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'hourly_rate', 'Hourly Rate', array('maxlength'=>15, 'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'months', 'Months', array('maxlength'=>15, 'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'estimated_hours', 'Est. Hrs', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    $this->form->add_input( 'monthly_hours', 'Monthly Hrs', array('maxlength'=>15,  'style' => 'width:80px;', 'align' => 'right') );
    
    $this->form->add_input( 'v1', 'Start Value', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );
    $this->form->add_input( 'v2', 'End Value', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );

    $this->form->add_input( 'e1', 'Start Expenses', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );
    $this->form->add_input( 'e2', 'End Expenses', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );

    $this->form->add_input( 'b1', 'Start Balance', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );
    $this->form->add_input( 'b2', 'End Balance', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );

    $this->form->add_input( 'h1', 'Start Hourly Rate', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );
    $this->form->add_input( 'h2', 'End Hourly Rate', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );

    $this->form->add_input( 'eh1', 'Start Estimated Hours', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );
    $this->form->add_input( 'eh2', 'End Estimated Hours', array('maxlength'=>15, 'style' => 'width:40px; float:left;') );

    $this->form->add_input( 'start_iec', 'Start Date (Int, Exp, Cont, Del, Status)', NULL, 'date'  );
    $this->form->add_input( 'end_iec', 'End Date (Int, Exp, Cont, Del, Status)', NULL, 'date'  );

    $this->form->add_input( 'org_search', 'Client', NULL, 'search' );
    $this->form->add_input( 'poc_search', 'Point of Contact', NULL, 'search' );
    $this->form->add_input( 'poc_search_val', '', NULL, 'hidden' );
    //$this->form->add_input( 'org_search_target', 'Target', NULL, 'search' );

    $this->form->add_input( 'start', 'Start Date', NULL, 'date'  );
    $this->form->add_input( 'end', 'End Date', NULL, 'date'  );

    $this->form->add_textarea( 'notes', 'Notes' );
    $this->form->add_input( 'bd_poc', 'BD POC' );

    $this->form->add_input( 'created_by', 'Created By' );
    $this->form->add_input( 'changed_by', 'Last Changed By' );


    $this->form->add_validation( 'name', V_REQUIRED );
    $this->form->add_validation( 'org_search', V_REQUIRED );
    $this->form->add_validation( 'start', V_DATE, 1 );

    $this->form->create_error_msg( 'name', E_VALUE_NOT_UNIQUE, 'This project name is already in use.' );


  }


  // ----------------------------------------------------------------------------------------
  function _pre_write_back( )
  {
    global $USER;

    // set changed by data 
    $this->data['fk_last_changed_user'] = $USER->get( 'id' );
    $this->data['last_changed'] = date( 'Y-m-d' );
    $this->data['start'] = CORE_date( $this->data['start'], F_DATE_SQL );
    $this->data['end']   = CORE_date( $this->data['end'], F_DATE_SQL );
    $this->data['value'] = currency_to_number($this->data['value']);
    $this->data['hourly_rate'] = currency_to_number($this->data['hourly_rate']);
  }

  // ----------------------------------------------------------------------------------------
  function _post_write_back( )
  {
    $this->write_back_users();
    $this->write_back_conferences();
    $this->write_back_collectors();
    $this->write_back_contractors();
  }

  // ----------------------------------------------
  function _post_load_data( $id )
  {
  	global $LANG;
  	if($this->data['prefix']>0){
  		$this->data['id_prefix'] = sprintf('%s-%06d', $LANG['prefix'][ $this->data['prefix'] ], $id );
  	} else {
  		$this->data['id_prefix'] = sprintf('   %06d', $id );  
  	}
  		
    $this->data['raw_value'] = $this->data['value'];
    $this->data['value'] = format_currency($this->data['raw_value']);
    $this->data['raw_hourly_rate'] = $this->data['hourly_rate'];
    $this->data['hourly_rate'] = format_currency($this->data['raw_hourly_rate']);
    $this->load_users( );
    $this->load_conferences( );
    $this->load_collectors( );
    $this->load_contractors( );
    $this->count_expenses( );

    if ($this->data['raw_hourly_rate']>0){
      $this->data['estimated_hours'] = number_format(($this->data['raw_value'] / $this->data['raw_hourly_rate']), 0);
    }
    
    if ($this->data['months']>0){
    	$this->data['monthly_hours'] = number_format(($this->data['estimated_hours'] / $this->data['months']), 0);
    }    
    
    $sql = "SELECT CONCAT_WS(' ', u.first_name, u.last_name ) AS created_by, CONCAT_WS(' ', u2.first_name, u2.last_name) AS changed_by
            FROM user_info AS u, user_info AS u2
            WHERE u.pk_id=".$this->data['fk_created_by_user']." AND u2.pk_id=".$this->data['fk_last_changed_user'];
    $result = mysql_query( $sql );
    if( $res = mysql_fetch_assoc( $result ) )
    {
    	$this->data['created_by'] = $res['created_by'];
    	$this->data['changed_by'] = $res['changed_by'];
    }
    
  }
  
  //=========================================================================================
  // Expenses
  // ----------------------------------------------------------------------------------------
  function count_expenses( )
  {
    $sql = "SELECT SUM(sum_expenses) AS sum_expenses, SUM(count_expenses) AS count_expenses FROM
            (SELECT SUM(int_amount) AS sum_expenses, COUNT(1) AS count_expenses
                        FROM expenses AS exp
            WHERE fk_project_id=".$this->id."
            UNION
            SELECT SUM(REPLACE(rate, '$', '')) AS sum_expenses, COUNT(1) AS count_expenses
              FROM interviews i
              INNER JOIN interview_projects ip ON i.pk_id = ip.fk_interview_id
              LEFT JOIN projects AS p ON p.pk_id=ip.fk_project_id
              WHERE ip.fk_project_id = ".$this->id." AND paid = 1) AS t";

    $result = mysql_query( $sql );
    while( $res = mysql_fetch_assoc( $result ) )
    {
      $this->data['raw_sum_expenses'] = $res['sum_expenses'];
      $this->data['sum_expenses'] = format_currency($res['sum_expenses']);
      $this->data['count_expenses'] = $res['count_expenses'];
    }
    
    // Conference value
    $sql = "SELECT SUM(int_amount) AS sum_conferences_value
   			FROM  conf_projs WHERE fk_project_id = ".$this->id;
    //echo $sql;
    $result = mysql_query( $sql );
    while( $res = mysql_fetch_assoc( $result ) )
    {
    	$this->data['raw_sum_conferences_value'] = $res['sum_conferences_value'];
    	$this->data['sum_conferences_value'] = format_currency($res['sum_conferences_value']);
    }
    
    $this->data['raw_profit'] = ($this->data['raw_value'] + $this->data['raw_sum_conferences_value']) - ($this->data['raw_sum_expenses']);
    $this->data['profit'] = format_currency($this->data['raw_profit']);
    
    if($this->data['raw_value']>0){
      $this->data['pct_spent'] =  round((($this->data['raw_sum_expenses']) / ($this->data['raw_value']) * 100), 2);
    } else {
      $this->data['pct_spent'] = 0;
    }
    
    $res['total_value'] = $this->data['raw_sum_conferences_value'] + $this->data['raw_value'];
    $this->data['raw_total_value'] = $res['total_value'];
    $this->data['total_value'] = format_currency($res['total_value']);    
  }

  //=========================================================================================
  // User/Projects
  // ----------------------------------------------------------------------------------------
  function load_users( )
  {
    $sql = "SELECT *, CONCAT_WS(' ', first_name, last_name) AS user_name
            FROM user_projects AS up
              LEFT JOIN user_info AS ui ON ui.pk_id=up.fk_user_id
            WHERE fk_project_id=".$this->id;

    $result = mysql_query( $sql );

    $ret_val = array( 'id'=>array(), 'name'=>array() );
    
    while( $res = mysql_fetch_assoc( $result ) )
    {
      $ret_val['id'][] = $res['fk_user_id'];
      $ret_val['name'][] = $res['user_name'];
 
    }

    $this->set( 'analysts', implode( '.', $ret_val['id'] ), F_PHP );
    $this->set( 'user_names', $ret_val['name'], F_PHP );
  }

  // ----------------------------------------------------------------------------------------
  function write_back_users( )
  {
    mysql_query( "DELETE FROM user_projects WHERE fk_project_id=".$this->id );

    $user_list = $this->get( 'analysts', F_PHP );

    if( is_string( $user_list ) )
      $user_list = explode( '.', $user_list );

    if( $user_list )
      foreach( $user_list as $user_id )
        mysql_query( "INSERT INTO user_projects ( fk_project_id, fk_user_id ) VALUES ( ".$this->id.", ".$user_id." )" );
  }

  //=========================================================================================
  // Conference/Projects
  // ----------------------------------------------------------------------------------------
  function load_conferences( )
  {
  	$sql = "SELECT *, name AS conference_name
            FROM conference_projects AS up
              LEFT JOIN conferences AS ui ON ui.pk_id=up.fk_conference_id
            WHERE fk_project_id=".$this->id;
  	
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
  	mysql_query( "DELETE FROM conference_projects WHERE fk_project_id=".$this->id );
  
  	$conference_list = $this->get( 'conferences', F_PHP );
  
  	if( is_string( $conference_list ) )
  		$conference_list = explode( '.', $conference_list );
  
  	if( $conference_list )
  		foreach( $conference_list as $conference_id )
  		mysql_query( "INSERT INTO conference_projects ( fk_project_id, fk_conference_id ) VALUES ( ".$this->id.", ".$conference_id." )" );
  }
  
  //=========================================================================================
  // Collectors/Projects
  // ----------------------------------------------------------------------------------------
  function load_collectors( )
  {
  	$sql = "SELECT *, CONCAT_WS(' ', first_name, last_name) AS user_name
            FROM collector_projects AS up
              LEFT JOIN user_info AS ui ON ui.pk_id=up.fk_user_id
  			  INNER JOIN users AS u ON (u.pk_id=up.fk_user_id AND u.active = 1)
            WHERE fk_project_id=".$this->id;
  	
  	$result = mysql_query( $sql );
  
  	$ret_val = array( 'id'=>array(), 'name'=>array() );
  
  	while( $res = mysql_fetch_assoc( $result ) )
  	{
  		$ret_val['id'][] = $res['fk_user_id'];
  		$ret_val['name'][] = $res['user_name'];
  
  	}
  
  	$this->set( 'collectors', implode( '.', $ret_val['id'] ), F_PHP );
  	$this->set( 'user_names', $ret_val['name'], F_PHP );
  }
  
  // ----------------------------------------------------------------------------------------
  function write_back_collectors( )
  {
  	mysql_query( "DELETE FROM collector_projects WHERE fk_project_id=".$this->id );
  
  	$user_list = $this->get( 'collectors', F_PHP );
  
  	if( is_string( $user_list ) )
  		$user_list = explode( '.', $user_list );
  
  	if( $user_list )
  		foreach( $user_list as $user_id )
  		mysql_query( "INSERT INTO collector_projects ( fk_project_id, fk_user_id ) VALUES ( ".$this->id.", ".$user_id." )" );
  }
  
  
  //=========================================================================================
  // Contractor/Projects
  // ----------------------------------------------------------------------------------------
  function load_contractors( )
  {
    $sql = "SELECT *, CONCAT_WS(' ', first_name, last_name) AS user_name
            FROM contractor_projects AS up
              LEFT JOIN contacts AS ui ON ui.pk_id=up.fk_contact_id
            WHERE fk_project_id=".$this->id;

    $result = mysql_query( $sql );

    $ret_val = array( 'id'=>array(), 'name'=>array() );
    
    while( $res = mysql_fetch_assoc( $result ) )
    {
      $ret_val['id'][] = $res['fk_contact_id'];
      $ret_val['name'][] = $res['user_name'];
 
    }

    $this->set( 'contractors', implode( '.', $ret_val['id'] ), F_PHP );
    $this->set( 'user_names', $ret_val['name'], F_PHP );
  }

  // ----------------------------------------------------------------------------------------
  function write_back_contractors( )
  {
    mysql_query( "DELETE FROM contractor_projects WHERE fk_project_id=".$this->id );

    $user_list = $this->get( 'contractors', F_PHP );

    if( is_string( $user_list ) )
      $user_list = explode( '.', $user_list );

    if( $user_list )
      foreach( $user_list as $user_id )
        mysql_query( "INSERT INTO contractor_projects ( fk_project_id, fk_contact_id ) VALUES ( ".$this->id.", ".$user_id." )" );
  }


}

?>
