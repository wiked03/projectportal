<? //============================================================ ?>
<div id="upd_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/vcard_delete.png" class="icon"/>Delete Status Update</h2>
   <div class="content">
<img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this Status Update?</p>

<form name="f_upd_del" id="f_upd_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="upd_del" />
  <input type="hidden" id="f_upd_del-upd_id" name="upd_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_upd_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('upd_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>
