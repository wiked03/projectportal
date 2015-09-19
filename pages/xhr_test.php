<?

$PAGE->title = "Home";
filter_page_vars( 0 );



$PAGE->add_style( 'style_forms.css' );
$PAGE->add_style( 'style_clarity.css' );

$PAGE->add_script( 'xmlhttpreq.js' );

load_view( 'pagehead' );
// -----------------------------------------------------------------------------------------
//
//echo $SESS->display_message( );


/*
    <input autocomplete="off" onblur="setTimeout( 'hide(\'f_login-username_popup\')', 1000 );" onkeyup="xhr_search( 'f_login-username', 'users.username' )" type="text" name="username" id="f_login-username" maxchars="50" <?=$PAGE->print_tag( 'username' )?> />

<div class="xhr_search_container">
<div class="xhr_search_result" id="f_login-username_popup"></div>
</div>
*/
?>
<div class="page_content">
  <div class="page_content_full">
<? //------------------------------------------------------------ ?>

   <h1>Home</h1>

  <form method="post" name="f_login" id="f_login" action="login">
   <input type="hidden" name="submit_form" value="1">
   <div class="form_item">
    <label for="f_login-username">Username:</label>
<div class="xhr_search_container">
    <input autocomplete="off" type="text" name="username" id="f_login-username" maxchars="50" <?=$PAGE->print_tag( 'username' )?> />

</div>
<?=SP_DIV?>
   </div>
   <div class="form_item">
    <label for="f_login-password" >Password:</label>
    <input type="password" name="password" id="f_login-password" maxchars="50" <?=$PAGE->print_tag( 'password', 0 )?> />
   </div>
   <div>
    <label for="f_login-remember" class="checkbox">
     <input type="checkbox" class="checkbox" name="remember" id="f_login-remember" value="1">
     Remember Me</label>
   </div>
   <div>
    <a href="javascript:Dom.get('f_login').submit()" class="button_red" <?=$PAGE->print_tag( 'submit', 0 )?>><span>Log In &raquo;</span></a>
    <a class="forgot_password" href="javascript:Dom.get('f_login').action='user/forgot_password';Dom.get('f_login').submit()">Forgot Password?</a>
   </div>
  </form>


<a onclick="var val=xhr_submit( 'f_login', 'localhost', 0, '' );alert(val);">TEST</a>




<script type="text/javascript">


//var my_search = new Calendar( 'my_cal', 'f_int-date_of_birth', 0 );


var user_search = new Xhr_search( 'user_search', 'f_login-username', 'users' );

</script>



<? //------------------------------------------------------------ ?>
  </div>
</div> <!-- end of page_content -->




<?

load_view( 'pagetail' );

?>