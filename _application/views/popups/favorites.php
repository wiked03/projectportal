<? //============================================================ ?>
<div id="favorites_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/star_add.png" class="icon"/>Add to Favorites</h2>
   <div class="content">
    <p>Enter a title for this favorite:</p>

<form name="f_fave" id="f_fave" method="post" action="<?=$path.$PAGE->var_string?>">
  <input type="hidden" name="submit_form" value="fave_add" />
  <input type="text" name="title" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_fave').submit()" class="button_small"><span>Save</span></a>

<a onclick="hide('favorites_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>