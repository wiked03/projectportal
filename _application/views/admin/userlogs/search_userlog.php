<?

global $LANG, $SESS;

$my_view = new View();

if( $search_string )
  echo '<div class="search_text" id="userlog_search_text">'.$search_string.'
<a onclick="hide(\'userlog_search_text\');show(\'userlog_search_form\');" class="hilight img search_add">Search</a>
 </div>';

else
  echo '<div id="userlog_search_text"><a onclick="hide(\'userlog_search_text\');show(\'userlog_search_form\');" class="hilight img search_add">Show Search form</a></div>';
?>

 

 <div class="search_form" id="userlog_search_form">
  <form method="post" name="f_userlog" id="f_userlog" action="<?=$path?>">
   <input type="hidden" name="submit_form" value="1">

<div class="left_side">
<?

echo $form->print_item( 'name' );

//echo $form->print_item( 'description' );

echo $form->print_item( 'created_by' );

?>
</div>

<div class="full_width">

<?
echo $form->print_item( 'start', 1, 'date' );
echo $form->print_item( 'end', 1, 'date end_date' );

?>

</div>

<?
//$col_names = $list->get_column_titles( );
//$overrides = $list->get_overrides( );

//$form->add_select( 'col', $col_names, '', array( 'multi'=>1, 'me'=>'sel_columns', 'override'=>$overrides ) );
//echo $form->print_select_popup( 'col' );
?>

<!--  
<div id="f_source-remember-checkbox">
  	<input type="checkbox" class="checkbox" name="remember" id="f_source-remember" value="1">
     Save Column Layout
</div>
-->

<a onclick="hide('userlog_search_form');show('userlog_search_text');" class="hilight img search_delete">Hide</a>


<script type="text/javascript">

var sel_log_name = new Select_multi( 'sel_log_name', 'f_userlog-name' );

/*
var sel_columns = new Select_multi( 'sel_columns', 'f_userlog-col' );
sel_columns.override_output = 'Select Columns';
sel_columns.default_value = '';
sel_columns.clear_all = '';

function hideSelColumns(){	
	var elem = document.getElementById('f_userlog-col_select');
	elem.style.display = 'none';
	sel_columns.clear = 0;
	sel_columns.visible = false;
}*/

//---------------

var sel_created_by = new Select_multi( 'sel_created_by', 'f_userlog-created_by' );
sel_created_by.clear_all = '';

var curDate = new Date();
var prevDate = new Date();

prevDate.setDate ( curDate.getDate() - 7 );
document.getElementById('f_userlog-start').value = prevDate.format("m/dd/yyyy");
document.getElementById('f_userlog-end').value = curDate.format("m/dd/yyyy");

var cal_start = new Calendar( 'cal_start', 'f_userlog-start', 1 );
var cal_end   = new Calendar( 'cal_end', 'f_userlog-end', 1 );

cal_start.link_date( 'lt', 'cal_end' );
cal_end.link_date( 'gt', 'cal_start' );

//---------------

<?
if( !$search_string )
{
?>
addLoadEvent( 'hide(\'userlog_search_text\');show(\'userlog_search_form\');' );
<?
}
?>


var isCtrl = false;

$(document).keyup(function (e) {
	if(e.which == 17) isCtrl=false;
}).keydown(function (e) {
	if(e.which == 17) isCtrl=true;
	if(e.which == 49 && isCtrl == true) {
		//Q1
    var year = new Date().getFullYear() 
    $('#f_userlog-start_iec').val("1/1/"+year);
    $('#f_userlog-end_iec').val("3/31/"+year);
		return false;
	}
	if(e.which == 50 && isCtrl == true) {
		//Q2
    var year = new Date().getFullYear() 
    $('#f_userlog-start_iec').val("4/1/"+year);
    $('#f_userlog-end_iec').val("6/30/"+year);
		return false;
	}
	if(e.which == 51 && isCtrl == true) {
		//Q3
    var year = new Date().getFullYear() 
    $('#f_userlog-start_iec').val("7/1/"+year);
    $('#f_userlog-end_iec').val("9/30/"+year);
		return false;
	}
	if(e.which == 52 && isCtrl == true) {
		//Q4
    var year = new Date().getFullYear() 
    $('#f_userlog-start_iec').val("10/1/"+year);
    $('#f_userlog-end_iec').val("12/31/"+year);
		return false;
	}
});


$(document).ready(function() {
    $('#f_userlog-name').keydown(function(event) {
        if (event.keyCode == 13) {
        	make_userlog_query();
            return false;
         }
    });
});

</script>


   <div class="buttons">
    <a onclick="make_userlog_query()" class="button_red"><span>Search &raquo;</span></a>
    <a onclick="reset_form('f_userlog')" class="button_gray"><span>Clear</span></a>
    <?=SP_DIV?>
   </div>

  </form>
 </div>
