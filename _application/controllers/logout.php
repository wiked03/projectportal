<?

//-------------------------------------------------------------------------------------------
// Page Handling
//-------------------------------------------------------------------------------------------
// logout, send headers, and redirect
$SESS->clear_data( );
$SESS->set_message( array('type'=>'success','text'=>'You have been logged out successfully.') );

// Create user log record
$sql = "INSERT INTO userlogs (name, fk_created_by_user, created) VALUES (2, ".$USER->get('id').", NOW())";
mysql_query( $sql );

$SESS->logout( );

header('Location: '.PATH_BASE.'home');

?>
