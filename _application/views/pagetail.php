<?

global $USER;

// create page tail
if( !$PAGE->no_tail )
{
  echo '
 <div id="page_tail">
  <p>'.n;

/*
  if( is_object( $USER ) )
    $u = $USER->get( 'level' );
  else
    $u = 0;
 
  // print menu links
  for( $i = 0; $i < count( $PAGE->menu[$u] ); $i ++ )
  { 
    $class = ' |';
    if( $i == count( $PAGE->menu[$u] ) - 1 )
      $class = '';
    
    echo '   <a href="'.$PAGE->menu[$u][$i]['link'].'">'.$PAGE->menu[$u][$i]['name'].'</a>'.$class.n; 
  }
  
  echo '  </p>'.n;
*/
  if( !in_array( $PAGE->type, array('login','signup') ) ){
  	echo '<span id="tail_copyright">'.$PAGE->program_name.' v'.$PAGE->program_version.' &copy; '.$PAGE->copy_date.' '.$PAGE->copy_owner.'. All rights reserved.</span>'.n;
  }
  
 // if( $tell_me )
    echo '<div id="tail_debug_text">'.$tell_me.'</div>'.n;
  
  echo ' </div> <!-- end of page_tail -->'.n;
  echo '</div> <!-- end page_outer -->'.n;

}


echo '</body>'.n.'</html>';
?>