<?

global $USER, $SESS;

if( is_object( $SESS ) && !$PAGE->no_redirect && !in_array( $PAGE->type, array('error', 'login', 'signup') ) )
{
  $SESS->set_redirect( PATH_SELF );
}

//===========================================================================================
// HTML HEAD SECTION
//-------------------------------------------------------------------------------------------

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<? if( !in_array( $PAGE->type, array('login','signup') ) ){ ?>
  <title><?=$PAGE->program_title?> | <?=$PAGE->title?></title>
<? } else { ?>
  <title><?=$PAGE->title?></title>
<? } ?>
   <?=$PAGE->meta?>
   <base href="<?=PATH_WEB?>" /> 
   <link rel="icon" href="favicon.ico" type="image/x-icon" />
   <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<?

// print RSS links
if( isset( $PAGE->rss ) )
  foreach( $PAGE->rss as $rss )
    echo '   <link rel="alternate" type="application/rss+xml" title="'.$rss['title'].'" href="'.$rss['link'].'">'.n;


// link in extra stylesheets
if( !$PAGE->stylesheets_off )
{
  if( isset($PAGE->style) )
    foreach( $PAGE->style as $style )
      echo '   <link rel="stylesheet" href="'.$style.'" type="text/css" />'.n;
}

// link in extra scripts
if( isset($PAGE->script) )
  foreach( $PAGE->script as $script )
    echo '   <script type="text/javascript" src="'.$script.'"></script>'.n;

// print inline css code
if( isset( $PAGE->inline_style ) )
  echo '   <style type="text/css" media="all">'.$PAGE->inline_style.'</style>'.n;

// print inline javascript code 
if( isset( $PAGE->inline_script ) )
  echo '   <script type="text/javascript" language="Javascript">'.$PAGE->inline_script.'</script>'.n;

// print rest of head
if( isset( $PAGE->head ) )
  echo $PAGE->head.n;

echo '</head>'.n;


//-------------------------------------------------------------------------------------------
// BODY tag
//-------------------------------------------------------------------------------------------

echo '<body';

// print body tag actions
if( gettype( $PAGE->body ) == 'array' )
{
  foreach( $PAGE->body as $event => $action )
    echo ' '.$event.'="'.$action.'"';
}
else
{
  echo ' '.$PAGE->body;
}

echo ' id="page_body">'.n;

//===========================================================================================
// PAGE HEAD - Title Bar
//-------------------------------------------------------------------------------------------
if( !$PAGE->no_head )
{
  echo '<div id="page_outer">';

  echo '<div id="page_titlebar">';
//  <div id="page_titlebar_left"></div>
  echo '<div id="page_titlebar_main">';
 
  if( !in_array( $PAGE->type, array('login','signup') ) ){
	 echo '
   		<div id="page_logo"><h1><a href="home"><span>'.$PAGE->program_name.'</span></a></h1></div>';
  }

// ----------- System Message
  //echo '<div id="page_system_msg">'.$SESS->display_message( ).'</div>';
  echo '<div id="page_system_msg"></div>';

?>
<script type="text/javascript">
addLoadEvent( 'get_system_msg( )' );
</script>
<?

//-------------------------------------------------------------------------------------------
// Menu Bar
//-------------------------------------------------------------------------------------------

?>
    <div id="page_menubar">
     
<?
  /*
<ul>
  for( $i = 0; $i < count( $PAGE['menu'] ); $i ++ )
  { 
    $class = '';
    if( $i == 0 )
      $class = ' class="first"';
    elseif( $i == count( $PAGE['menu'] ) - 1 )
      $class = ' class="last"';
    
    echo '      <li><a href="'.$PAGE['menu'][$i]['link'].'"'.$class.'><span>'.$PAGE['menu'][$i]['name'].'</span></a></li>'.n; 
  }
</ul>

*/

  if( is_object( $USER ) )
    $u = $USER->get( 'level' );
  else
    $u = 0;

  for( $i = 0; $i < count( $PAGE->menu[$u] ); $i ++ )
  { 
    $class = '';
    if( $i == count( $PAGE->menu[$u] ) - 1 )
      $class = ' class="last"';
    
    echo '<a href="'.$PAGE->menu[$u][$i]['link'].'"'.$class.'>'.$PAGE->menu[$u][$i]['name'].'</a>'; 
  }


?>
     
    </div>
<?

//-------------------------------------------------------------------------------------------
// login link

  if( !in_array( $PAGE->type, array('login','signup') ) && is_object( $USER ) ) 
  { 
    echo '<div id="page_login_info">';
   
    if ( $USER->id )
      echo 'Logged in as <b>'.$USER->get( 'username', F_HTM ).'</b>. <a class="img_small logout" href="logout">Log Out</a>';
    else
      echo '<a class="img_small login" href="login">Log In</a>';
   
    echo "</div>";
  }


//-------------------------------------------------------------------------------------------
// end of title bar
  
  echo '
   </div>';
//  <div id="page_titlebar_right"></div>

  echo '</div>';


}


?>
