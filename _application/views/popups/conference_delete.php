<? //============================================================ ?>
<div id="conference_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/user_delete.png" class="icon"/>Delete Conference</h2>
   <div class="content">
<img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this conference?</p>

<form name="f_conference_del" id="f_conference_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="conference_del" />
  <input type="hidden" id="f_conference_del-conference_id" name="conference_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_conference_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('conference_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>