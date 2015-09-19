<? //============================================================ ?>
<div id="del_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/vcard_delete.png" class="icon"/>Delete Deliverable</h2>
   <div class="content">
<img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this Deliverable?</p>

<form name="f_del_del" id="f_del_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="del_del" />
  <input type="hidden" id="f_del_del-del_id" name="del_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_del_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('del_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>
