<html>
<head>
<title>Project Portal DB - <?=$type?> List</title>
<style type="text/css">
table
{
  border-bottom: 1px solid #808080;
  border-left: 1px solid #808080;
  border-collapse: collapse;
}
td, th
{
  border-right: 1px solid #808080;
  border-top: 1px solid #808080;
  font-size: 11px;
}
table img
{
  display: none;
}
th a, th a:visited
{
  color: #000000;
  text-decoration: none;
}
</style>
<script type="text/javascript"> 
function Print()
{ 
  document.body.offsetHeight;window.print()
};
</script>
</head>
<body onload="Print()">

<img src="<?=PATH_WEB?>img/pp-logo2.png"
width=199 height=68 alt="Project Portal DB">
<hr/>
<font size=+2>
<b><?=$type?> List</b></font><br>
<font size=-1><?=$search_string?></font>
<hr/>

<?

echo $list->print_table( );

?>
</body>
</html>