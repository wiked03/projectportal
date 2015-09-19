<?

header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="pp_export_'.strtolower($type).'s.csv"');


echo $list->print_csv( );
