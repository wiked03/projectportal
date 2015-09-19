<?

if( $_POST['submit_form'] )
{
	echo "form submitted!!";
}
?>n" id="f_login" action="form_test.php">
   <input type="hidden" name="submit_form" value="1">

   <div class="form_item">
    <label for="f_login-username">Username:</label>
    <input type="text" name="username" id="f_login-username" maxchars="50"  />
   </div>

   <div class="form_item">
    <label for="f_login-password" >Password:</label>
    <input type="password" name="password" id="f_login-password" maxchars="50"  />
   </div>

   <div class="form_item">
    <label for="f_login-remember" class="checkbox">
     <input type="checkbox" class="checkbox" name="remember" id="f_login-remember" value="1">
     Remember Me</label>
   </div>

   <div class="buttons">
   <input type="submit" value="click" name="submit">
    <a href="javascript:Dom.get('f_login').submit()" class="button_red" ><span>Log In &raquo;</span></a>
    <a class="forgot_password" href="user/forgot_password">Forgot Password?</a>
   </div>
  </form>
</div>
</html>


<html>
  <form method="post" name="f_logi