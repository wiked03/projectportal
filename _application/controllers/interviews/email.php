<?
$SESS->require_login( $USER, PATH_SELF );
filter_page_vars( 2 );

load_model( 'interview' );
load_model( 'user_info' );
load_model( 'contact' );
load_model( 'organization' );
load_lib( 'email' );

$my_obj  = new Interview( 0, 1 );
if( !$my_obj->load_data( $PAGE->vars[0] ) )
{
  $SESS->redirect_msg( 'failure', 'Interview not found.' );
}

$my_contact = new Contact( );
$my_org = new Contact_org( );

$my_contact->load_data($my_obj->get('fk_contact_id'));
$my_org->load_data(array($my_obj->get('fk_contact_id'), $my_contact->get('primary_org')));

$result = True;

// Extract list of users
$users = explode('.', $PAGE->vars[1]);
foreach ($users as $user_id) {

	$my_user = new User( );	
	if( !$my_user->load_data( $user_id ) )
	{
		$SESS->redirect_msg( 'failure', 'User not found.' );
	}
		
	$form = &$my_obj->form;
	
	$id = $my_obj->get( 'id' );
	$my_view = new View();
	
	// if user is analyst, lock contact name
	if( $USER->get('level')<=2 && ($my_obj->get('fk_created_by_user') != $USER->get('id') && !$my_view->on_project( $USER->get('id'), $my_obj->get('fk_contact_id') )) )
	{
		$name_lock = true;
	}
	
	global $LANG;
	
	$message = '';
	$message .= '<html><body>';
	$message .= '<b>Interview ID</b>'.":";
	
	$int_id   = $LANG['source_types_short'][$my_view->get( 'contact_type', $form->get('fk_contact_id') )].
	'-'.sprintf( '%03d', $form->get('fk_contact_id') ).
	'-'.$form->get('int_number');
	$message .= $int_id.'<br>';
	
	
	$message .= '<b>'.n.$form->labels['contact']."</b>:";
	if( $form->get( 'is_source' ) == 3 )
		$message .= $LANG['anonymous'];
	elseif( $name_lock )
	$message .= $LANG['source_hidden'];
	else
	{
		$message .= $form->get( 'contact', F_PHP );
		if( $form->get('title') )
			$message .=  ' - '.$form->get('title');
		if( $form->get('contact_org') )
			$message .=  ', '.$form->get('org_name');
	}
	$message .= '<br>';
	
	$message .= '<b>'.n.$form->labels['analyst']."</b>:";
	$message .= $form->get( 'analyst', F_PHP );
	$message .= '<br>';
	
	$message .= '<b>'.n.$form->labels['int_date']."</b>:";
	$message .= CORE_date( $form->get( 'int_date' ), G_DATE_FORMAT );
	$message .= '<br>';
	
	$location = "";
	$location .= $my_org->get( 'city' ) . ', ';
	$location .= $my_org->get( 'state' ) . ', ';
	$location .= $my_org->get( 'country' );
	$message .= '<b>'.n."Location</b>:";
	$message .= $location;
	$message .= '<br>';
	
	if( $form->get('projects') )
	{
		$vals = Array();
		$values = Array();
		$projects = "";
		$vals = explode( '.', $form->get('projects') );
		foreach( $vals as $val )
			$values[] = $my_view->get( 'project', $val, F_PHP );
		$projects = implode( ', ', $values );
	
		$message .= '<b>'.n.$form->labels['projects']."</b>:";
		$message .= $projects;
	}
	$message .= '<br>';
	
	if( $form->get( 'rate' ) )
	{
		$message .= '<b>'.n.$form->labels['rate']."</b>:";
		$message .= $form->get( 'rate', F_PHP );
		$message .= '<br>';
	
	
		$message .= '<b>'.n.$form->labels['paid']."</b>:";
		$message .= $LANG['yes_null'][$form->get( 'paid' )];
		$message .= '<br>';
	}
	if( $form->get( 'source_comments' ) != '' )
	{
		$message .= '<b>'.n.n.$form->labels['source_comments']."</b>:";
		$message .= $form->get( 'source_comments', F_PHP );
		$message .= '<br>';
	}
	if( $form->get( 'analyst_comments' ) != '' )
	{
		$message .= '<b>'.n.n.$form->labels['analyst_comments']."</b>:";
		$message .= $form->get( 'analyst_comments', F_PHP );
		$message .= '<br>';
	}
	
	if( $form->get( 'int_background' ) != '' )
	{
		$message .= '<b>'.n.n.$form->labels['int_background']."</b>:";
		$message .= $form->get( 'int_background', F_PHP );
		$message .= '<br>';
	}
	
	if( $form->get( 'confidential' ) != '' )
	{
		$message .= '<b>'.n.n.$form->labels['confidential']."</b>:";
		$message .= $form->get( 'confidential', F_PHP );
		$message .= '<br>';
	}
	
	if( $form->get( 'int_notes' ) != '' )
	{
		$message .= '<b>'.n.n.$form->labels['int_notes']."</b>:";
		$message .= $form->get( 'int_notes', F_PHP );
		$message .= '<br>';
	}
	
	
	$message .= '</body></html>';
	
	// Change class by inline CSS so they are displayed correctly by the email clients
	$message = str_replace('class="marker"', 'style="background-color: yellow;"', $message);
	$message = str_replace('<blockquote>', '<blockquote style="font-style: italic;">', $message);
		
	$my_user_info = new User_info(  );
	$my_user_info->load_data( $my_user->get('id') );
	$me = new User_info( );
	
	require_once 'lib/swift_required.php';
	
	//$transport = Swift_SmtpTransport::newInstance('localhost', 2025);
	$transport = Swift_SmtpTransport::newInstance('localhost', 25);
	$mailer = Swift_Mailer::newInstance($transport);
	
	$subject = "Emailing: Interview ".$int_id;
	$email_message = Swift_Message::newInstance($subject);
	
	if( $me->load_data($USER->get('id')) ){
		$email_message->setFrom(array('interviews@replaceme.com' => $me->get( 'first_name' )." ".$me->get( 'last_name' )));
		$email_message->setReplyTo(array(trim($USER->get( 'email' )) => $me->get( 'first_name' )." ".$me->get( 'last_name' )));
	} else
		$email_message->setFrom(array('noreply@replaceme.com' => 'Project Portal Database'));
	
	$email_message->setTo(array(trim($my_user->get('email'))));
	
	// Or set it after like this
	$email_message->setBody($message, 'text/html');
	
	$result = $result && $mailer->send($email_message);
	//$result = $mailer->send($email_message);
}


if( $result )
{
  $SESS->redirect_msg( 'success', 'Interview sent to '.count($users).' users.' );
}
else
{
  $SESS->redirect_msg( 'failure', 'There was a problem emailing the interview.' );
}
