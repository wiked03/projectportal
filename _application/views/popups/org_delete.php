<? //============================================================ ?>
<div id="org_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/building_delete.png" class="icon"/>Delete Organization</h2>
   <div class="content">
     <img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this organization?</p>

<form name="f_org_del" id="f_org_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="org_del" />
  <input type="hidden" id="f_org_del-org_id" name="org_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_org_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('org_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>