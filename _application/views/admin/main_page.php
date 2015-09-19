<?
global $SESS;

echo $SESS->display_message( );
?>
<div id="f_user_processing" style="display:none" class="system_msg processing">Processing entry&hellip;</div> 
<div id="f_user_error" <?=( $err_msg ? '' : 'style="display:none" ')?>class="system_msg failure"><?=$err_msg?></div>


<h3>Page 1</h3>
