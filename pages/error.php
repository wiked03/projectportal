<?
//=========================================================================================
// Voice of Physicians
//=========================================================================================

$error_code = $_SERVER['REDIRECT_STATUS'];

switch( $error_code )
{
  case( '403' ):
    $error_text = "Forbidden";
    $error_desc = "What are you doing in there!? You're not allowed! Get out!";
    $error_code = "403";
    break;
  case( '404' ):
  default:
    $error_text = "File Not Found";
    $error_desc = "Sorry, that file was not found. Try looking for something else.";
    $error_code = "404";
}


$PAGE->title = "Error ".$error_code." - ".$error_text;
$PAGE->type = 'error';

load_view( 'pagehead' );

?>

<div class="page_content">
 <div class="page_content_full">
<? //------------------------------------------------------------ ?>

<h2 class="underline">Error - <?=$error_code?> - <?=$error_text?></h2>
<p><?=$error_desc?></p>
<a href="javascript:history.back(-1)" style="float:left;margin-left:200px;" class="button_red"><span>&laquo; Back</span></a>
<a href="<?=PATH_WEB?>home" style="float:left;margin-left:200px;" class="button_red"><span>Home &raquo;</span></a>

<? //------------------------------------------------------------ ?>
 </div>
</div> <!-- end of page_content -->

<?

$PAGE->disable_analytics = true;
load_view( 'pagetail' );

?>