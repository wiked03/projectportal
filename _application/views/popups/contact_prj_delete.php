<? //============================================================ ?>
<div id="contact_prj_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/vcard_delete.png" class="icon"/>Remove Project</h2>
   <div class="content">
<img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to remove this project?</p>

<form name="f_contact_prj_del" id="f_contact_prj_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="contact_prj_del" />
  <input type="hidden" id="f_contact_prj_del_contact_id" name="contact_id" value="" />
  <input type="hidden" id="f_contact_prj_del_prj_id" name="prj_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_contact_prj_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('contact_prj_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>
