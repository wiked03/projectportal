<?
$q_str = CORE_decode( $_POST['value'], F_URI );
$value = CORE_encode( $q_str, F_SQL2 );

echo $value.N.N;

    $value = preg_replace( '/(.*;[\s]*)/', '', $value );

echo $value.N.N;