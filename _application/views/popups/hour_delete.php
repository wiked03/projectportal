<? //============================================================ ?>
<div id="user_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/user_delete.png" class="icon"/>Delete Hour</h2>
   <div class="content">
<img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this hour?</p>

<form name="f_user_del" id="f_user_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="user_del" />
  <input type="hidden" id="f_user_del-user_id" name="user_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_user_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('user_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>
