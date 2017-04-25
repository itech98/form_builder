<?php
include_once('Form_Builder_Validation.php');

if (isset($_GET['pname']))  {$pname  = $_GET['pname'];}  else {$pname='';}
if (isset($_GET['pemail'])) {$pemail = $_GET['pemail'];} else {$pemail='';}
if (isset($_GET['ppass']))  {$ppass  = $_GET['ppass'];}  else {$ppass='';}
if (isset($_GET['pcap']))   {$pcap   = $_GET['pcap'];}   else {$pcap='';}
// Set arrays for validation.
// REQUIRED FIELDS.
$required       =   array( 'pname'  , 'pemail'  , 'ppass'   );
// TYPES ALLOWED FOR FIELDS.
$fields         =   array( 'pname' =>'string' , 'pemail' =>'email' ,  'ppass' =>'string' );
// ACTUAL FIELD VALUES.
$form_fields    =   array( 'pname' => $pname , 'pemail' => $pemail  ,  'ppass' => $ppass, 'captcha'=>$pcap );
//$pdat           =   array( 'pname' => $pname , 'pemail' => $pemail  ,  'ppass' => $ppass );
$valid = new Form_Builder_Validation( $fields , $required , $form_fields );
// Check for mandatory fields....
$valid->validate_mandatory();
// Check for valid entries - e.g. valid email etc..
$valid->validate_entries();
// Valid captcha.
$valid->validate_captcha();
// GET THE ERRORS ARRAY...
$errors = $valid->get_errors();
// just empty braces??
if  (!empty($errors))  {
        $ret = array( 'errors'  =>  $errors);
} else {
        $ret = array( 'data'    =>  $form_fields);
}
print_r( json_encode($ret));


?>
