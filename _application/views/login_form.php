<script type="text/javascript">
  $(document).ready(function() {
    $('#f_login-password').keydown(function(event) {
        if (event.keyCode == 13) {
            this.form.submit();
            return false;
         }
    });
  });
</script>

<div id="login_form">
<?

if( $err_msg )
  echo '  <div class="system_msg failure">'.$err_msg.'</div>';

?>
  <form method="post" name="f_login" id="f_login" action="login">
   <input type="hidden" name="submit_form" value="1">

   <div class="form_item">
    <label for="f_login-username">Username:</label>
    <input type="text" name="username" id="f_login-username" maxchars="50" <?=$PAGE->print_tag( 'username' )?> />
    <?=SP_DIV?>
   </div>

   <div class="form_item">
    <label for="f_login-password" >Password:</label>
    <input type="password" name="password" id="f_login-password" maxchars="50" <?=$PAGE->print_tag( 'password', 0 )?> />
    <?=SP_DIV?>
   </div>

   <div class="form_item">
    <label for="f_login-remember" class="checkbox">
     <input type="checkbox" class="checkbox" name="remember" id="f_login-remember" value="1">
     Remember Me</label>
     <?=SP_DIV?>
   </div>

   <div class="buttons">
    <a href="javascript:Dom.get('f_login').submit()" class="button_red" <?=$PAGE->print_tag( 'submit', 0 )?>><span>Log In &raquo;</span></a>
    <a class="forgot_password" href="user/forgot_password">Forgot Password?</a>
   </div>
  </form>
</div>
