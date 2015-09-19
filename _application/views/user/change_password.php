<? global $LANG, $REGEX, $USER; ?>

<div id="login_form">

  <h3>Change Password for <?=$first_name?> <?=$last_name?></h3>
  <? if( $USER->get( 'level' ) < 5 ) { ?>
  	<form method="post" name="f_login" id="f_login" action="user/change_password">
  <?} else {?>
  	<form method="post" name="f_login" id="f_login" action="user/change_password/<?=$user_to_edit?>">
  <?} ?>
  	<input type="hidden" name="submit_form" value="1">
<?
if( $temp )
{
?>
   <div>
     <p>Your account currently has a temporary password.  Please enter a new one.</p>
   </div>
<?
}
else
{
	if( $USER->get( 'level' ) < 5 ) {
?>
   <div class="form_item">
    <label for="f_login-password" >Current Password:</label>
    <input type="password" name="password" id="f_login-password" maxchars="50"/>
   </div>
<?
	}
}
?>
   <div class="form_item">
    <label for="f_login-new_password" >New Password:</label>
    <input type="password" name="new_password" id="f_login-new_password" maxchars="50"/>
   </div>
   
	<div class="form_item">
	   <p><b>Passwords</b> must be at least 8 characters long, and have 1 number, 1 lowecase letter and 1 uppercase letter.</p>
	</div>

   <div class="buttons">
    <a href="javascript:Dom.get('f_login').submit()" class="button_red" ><span>Submit &raquo;</span></a>
   </div>
   

   
  </form>
</div>


<?

//    <input type="submit" name="submit_form" onclick="document.getElementById('f_login').submit();" value="Login"/>
  
?>
