<? //============================================================ ?>
<div id="project_del_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/report_delete.png" class="icon"/>Delete Project</h2>
   <div class="content">
     <img src="img/icons/big/warning.png" class="icon"/>
    <p>Are you sure you want to delete this project?</p>

<form name="f_proj_del" id="f_proj_del" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="proj_del" />
  <input type="hidden" id="f_proj_del-proj_id" name="proj_id" value="" /><?=SP_DIV?>
 <br/>
</form>

<a onclick="Dom.get('f_proj_del').submit()" class="button_small"><span>Delete</span></a>

<a onclick="hide('project_del_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>