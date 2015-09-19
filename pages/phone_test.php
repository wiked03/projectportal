<?

$PAGE->add_style( 'style_forms' );

load_view( 'pagehead' );

//echo '<div style="width:300px; height:200px; overflow: auto">';
?>
<div class="page_content">
 <div class="page_content_full">
<? 

echo CORE_make_date( 'lw', 0, G_DATE_FORMAT );
echo N.N.N.N;





$phone = '(312) 608-5655';

echo 'phone: '.$phone.N;

echo 'after: '.CORE_phone( $phone, F_SQL ).N;

echo 'after: '.CORE_phone( $phone, F_HTM ).N.N;


$error_msgs = preg_replace( '/\%NAME\%/', 'Joe', $LANG['errors'] );

$error_msgs[2] = "error 2";

$error_msgs[10] = "error 10";


$validate[] = array( "type"=>V_REQUIRED );
$validate[] = array( "type"=>V_REGEX, "regex"=>'/some\.thing/i', "warn"=>true );
$error_array[] = array( "id"=>"item1", "validate"=>$validate,"msgs"=> $error_msgs );

print_r( $error_array );

echo N.N.json_encode( $error_array );

echo N.N.'error message: '.$error_array[0]['msgs'][2];  // obj[0].msgs[2]


$form = new CORE_Form( 'myid' );

$form->add_input( 'first_name', 'First Name', array('maxlength'=>30) );
$form->add_validation( 'first_name', V_REQUIRED, true );
$form->add_validation( 'first_name', V_REGEX, false, $REGEX['phone'] );
echo N.N.$form->get_validation_json( );

//echo '</div>';

echo '<div class="custom_select"><div class="input">Hello</div></div>';

?>
 </div>
</div> <!-- end of page_content -->
<?

load_view( 'pagetail' );