<? //============================================================ ?>
<div id="favorites_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/star_delete.png" class="icon"/>Delete Favorite</h2>
   <div class="content">
    <p>Are you sure you want to delete this favorite?</p>

<form name="f_fave_del" id="f_fave_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="fave_del" />
  <input type="hidden" id="f_fave_del-fave_id" name="fave_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_fave_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('favorites_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>