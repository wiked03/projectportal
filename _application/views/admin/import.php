<div>
<?
if( $err_msg )
  echo '  <div class="system_msg failure">'.$err_msg.'</div>';
if( $success_msg )
  echo '  <div class="system_msg success">'.$success_msg.'</div>';
?>
  <h3>Import from CSV</h3>
  <form method="post" name="f_import" id="f_import" action="admin/import" enctype="multipart/form-data">
   <input type="hidden" name="submit_form" value="1">

   <div>
    <label for="f_import-import_file" >CSV File to Import:</label>
    <input type="file" name="import_file" id="f_import-import_file" maxchars="50" <?=$PAGE->print_tag( 'import_file', 0 )?> />
   </div>

   <div>
    <a href="javascript:Dom.get('f_import').submit()" class="button_red" <?=$PAGE->print_tag( 'submit', 0 )?>><span>Submit &raquo;</span></a>
   </div>
  </form>
</div>
