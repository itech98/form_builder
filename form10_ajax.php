<?php
include_once('includes/Form_Builder.php');
$form = new Form_Builder();
$form->addForm  ( "form10" , "post" , "example_form10.php"  );
$form->addGeneralField( "<fieldset>" );
$form->addLabel ( "Enter name" );
$form->addInput ( "name" ,  "" , "text" );
$form->addGeneralField('<br />');
$form->addLabel ( "Email Address" );
$form->addInput( "email");
$form->addGeneralField('<br />');
$form->addLabel ( "Password" );
$form->addInput ( "password", "" ,"password" );
$form->addGeneralField('<br />');
$form->addCaptcha();
$form->addGeneralField('<br />');
$form->addSubmit( "btnSubmit" , "OK", array('onclick'=>"JavaScript:process_form(); return false;") );
$form->addGeneralField('</fieldset>');
$form->EndForm();
// OUTPUT THE FORM.
echo $form;
?>