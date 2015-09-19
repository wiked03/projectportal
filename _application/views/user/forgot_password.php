<div id="login_form">
  <h3>Password Reset Request</h3>
  <form method="post" name="f_login" id="f_login" action="user/forgot_password">
   <input type="hidden" name="submit_form" value="2">
   <div class="form_item">
    <label for="f_login-email">Email Address:</label>
    <input type="text" name="email" id="f_login-email" maxchars="50" <?=$PAGE->print_tag( 'email' )?> />
    <?=SP_DIV?>
   </div>
   <div class="buttons">
    <a href="javascript:Dom.get('f_login').submit()" class="button_red"><span>Submit &raquo;</span></a>
   </div>
  </form>
</div>
