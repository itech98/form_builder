<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include('includes/Form_Builder_Validation.php');


$formData = array( 'height' => '6.8' ,  'date'=>'2017-04-24' , 'age'=>'34');
$test = new Form_Builder_Validation( array() , array() , $formData );
echo '<h1>VALIDATION TESTS</h1>';
//
// EMAIL TEST
//
echo '<h2><b>EMAIL TEST</b></h2>';
$t=  array(''=>'email', null=>'email' , 'ianims@hotmail.com'=>'email', 'df df.cim'=>'email' , 'sd sd@sd.com'=>'email' , '34'=>'email' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is not a email!!!'; } else { $res[]=$key. ' IS A EMAIL!!'; }
}
print_r( $res );

// #################################

echo '<h2><b>NUMBER TEST</b></h2>';
$t=  array(''=>'number', null=>'number' , '34'=>'number', 34=>'number' , '334dfdf'=>'number' , '34'=>'number' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is not a Number!!!'; } else { $res[]=$key. ' IS A NUMBER!!'; }
}
print_r( $res );


// #################################

echo '<h2><b>STRING TEST</b></h2>';
$t=  array(''=>'string', null=>'string' ,  '34'=>'string', 'hello me'=>'string' , 'SDSW343DS@}:L'=>'string' , '34'=>'string' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is not a String!!!'; } else { $res[]=$key. ' IS A STRING!!'; }
}
print_r( $res );

// #################################

echo '<h2><b>IP ADDRESS</b></h2>';
$t=  array(''=>'ip_address', '34.34'=>'ip_address' ,  '10.0.0.1'=>'ip_address');
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is not a IP Addr!!!'; } else { $res[]=$key. ' IS A IP ADDR!!'; }
}
print_r( $res );


// #################################

echo '<h2><b>INT</b></h2>';
$t=  array(''=>'integer', '34.34'=>'integer', '12'=>'integer',  10.3434500=>'integer',23=>'integer' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is not a INT!!!'; } else { $res[]=$key. ' IS A INT!!'; }
}
print_r( $res );


// #################################

echo '<h2><b>DATE</b></h2>';
$t=  array('23/02/2015'=>'date', '2017-03-22'=>'date', '3422324'=>'date','eeee-34-23'=>'date' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is NOT Brit Date !!!'; } else { $res[]=$key. ' IS A BRIT DATE!!'; }
}
print_r( $res );

echo '<h2><b>TIME</b></h2>';
$t=  array('342df'=>'time', '09'=>'time','02:33'=>'time', '14:50'=>'time' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is NOT Valid Time !!!'; } else { $res[]=$key. ' IS A VALID TIME!!'; }
}
print_r( $res );


// #################################

echo '<h2><b>POSTCODE</b></h2>';
$t=  array('s4dfdfd4'=>'postcode', 'ss25hr'=>'postcode' , 'ss2 5hr'=>'postcode' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is NOT PCODE !!!'; } else { $res[]=$key. ' IS A PCODE!!'; }
}
print_r( $res );



// #################################

echo '<h2><b>ZIPCODE</b></h2>';
$t=  array('342df'=>'zipcode', 'ss25hr'=>'zipcode','02201-2005'=>'zipcode' );
$res=  array();
foreach ($t as $key=>$val ) {
        $r = $test->check_value( $val , $key );
        if (  $r == true ) { $res[]=$key.' is NOT ZIPCODE !!!'; } else { $res[]=$key. ' IS A ZIPCODE!!'; }
}
print_r( $res );

// ###################################
echo '<h2><b>RANGES TEST</b></h2>';
//$test->form_data['height']='6.8';
$value = 6.8;
$r = $test->validate_IsValid( $value , 'height' , 'equal' );
if (  $r == true  ) { $res = 'height is equal to '.$value; } else { $res = 'height  IS NOT Equal To '.$value; }
echo $res;
echo '<br />';

//$test->form_data['date'] = '2017-04-24';
//$value = date("Y-m-d");
$value =  '2017-04-18';
$r = $test->validate_IsValid( $value , 'date' , 'greater_than' );
if (  $r == true ) { $res = 'DATE IS GTR'; } else { $res = 'DATE IS LESS THAN  today'; }
echo $res;




// ###################################
echo '<h2><b>BETWEEN TEST</b></h2>';
//$test->form_data['age']=43;
$res = $test->validate_IsBetween( 'age' , $formData['age'] ,'21' , '65' , true );
if ( $res== true  ) { echo 'Age is in Working Range!!';  } else { echo 'NOT IN WORK RANGE !!'; }


?>