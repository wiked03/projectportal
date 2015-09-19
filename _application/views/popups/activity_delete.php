<? //============================================================ ?>
<div id="act_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/phone_delete.png" class="icon"/>Delete Activity</h2>
   <div class="content">
     <img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this activity?</p>

<form name="f_act_del" id="f_act_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="act_del" />
  <input type="hidden" id="f_act_del-act_id" name="act_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_act_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('act_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>