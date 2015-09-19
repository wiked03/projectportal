<?

global $LANG, $SESS;


?>

 <div id="user_form">
  <h1>
    <img src="img/icons/big/user_<?=strtolower($add_edit)?>.png" />
    Act as a Different User
  </h1>
  
  <?php /* if($another_user){ ?>
  	<table>
  		<tr>
  			<td style='padding-left:20px;'><h4>Acting as User: <?=$another_user->full_name()?></h4></td>
  			<td style='padding-left:10px;'><a href='javascript:disable_user()'>Disable</a></td>
  		</tr>
  	</table>
	<?php } */?>
	
  <form method="post" name="f_user" id="f_user" action="admin/users/change/">
   <input type="hidden" name="submit_form" value="1">
   <input type="hidden" name="disable_another_user" id="disable_another_user" value="0">

<div class="left_side">
<?

echo $form->print_item( 'users' );

?>
</div>

<script type="text/javascript">
var user_form = new Validate( 'user_form', 'f_user', <?=$form->get_validation_json( )?> );
user_form.back_link = "<?=$SESS->get_redirect()?>";
</script>

   <div class="buttons">
    <a href="javascript:user_form.validate()" class="button_red"><span>Save &raquo;</span></a>
    <a href="javascript:user_form.cancel()" class="button_gray"><span>&laquo; Cancel</span></a>
    <?=SP_DIV?>
   </div>
  </form>
 </div>

 <script type="text/javascript">
function disable_user()
{
	document.f_user.disable_another_user.value = "1";
  document.f_user.submit();
}
</script>