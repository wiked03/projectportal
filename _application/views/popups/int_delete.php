<? //============================================================ ?>
<div id="int_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/comments_delete.png" class="icon"/>Delete Interview</h2>
   <div class="content">
     <img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this interview?</p>

<form name="f_int_del" id="f_int_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="int_del" />
  <input type="hidden" id="f_int_del-int_id" name="int_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_int_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('int_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>