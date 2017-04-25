<?php
//
// basic contact form
//
echo '<h1>basic form 3 -- Basic contact form with name, email, message + captcha.</h1>';
echo '<h3>Form with NAME, EMAIL , MESSAGE, CAPTCHA.</h3>';
echo '      <p>1. Generates a form with Name+Email+Message fields</p>';
echo '      <p>2. check that the Name+Email+Message+Captcha are entered</p>';
echo '      <p>3. that the Name+Email are a string and email</p>';
// include main class.
include_once('Form_Builder.php');


try  {
    // BUILD NEW FORM.
    $required = array( 'name' , 'email' , 'message' );
    $fields   = array( 'name'=>'string' , 'message'=>'string' ,  'email'=>'email' );
    $form = new Form_Builder( true , true );
    $form->process_submitted_data( $fields , $required );
    // FORM SUBMITTED?
    if(isset($form->form_data['submit'])) {
                // VALIDATION.
                // CHECK FOR MANDATORY FIELD VALUES.
                $form->validate_mandatory();
                // CHECK FOR VALID ENTRIES -- e.g. field is number etc.
                $form->validate_entries();
                // VALIDATE CAPTCHA..
                $form->validate_captcha();
                // VALIDATE SECURITY CODE
                $form->validate_security_code();
                // CLEAN THE DATA...
                $form_data  = $form->clean_data($form->submitted_data);

                // GET ANY ERRORS AND DISPLAY.....
                $errors = $form->get_errors('base64');
                if(!empty($errors)){
                    foreach( $errors as $r ) {
                        echo '<font color="red">'.$r.'</font><br />';
                    }
                } else {
                    // DATA OK SO OUTPUT DATA....
                    echo '<h3>FORM DATA</h3>';
                    foreach ($form->form_data as $key=>$val ) {
                        echo "$key = $val <br />";
                    }
                    exit;
                }
    } 
    $form->addForm  ( "form3" , "post" , "example_form3.php"  );
    $form->addGeneralField( "<fieldset>" );
    $form->addLabel ( "Enter name" );
    $form->addInput ( "name" ,  "" , "text" );
    $form->addGeneralField('<br />');
    $form->addLabel ( "Email Address" );
    $form->addInput( "email");
    $form->addGeneralField('<br />');
    $form->addLabel ( "MESSAGE:" );
    $form->addTextArea ( "message", "" , 6 , 40 );
    $form->addGeneralField('<br />');
    $form->addCaptcha( '<div style="float:left;">','</div>','<div style="float:left;">','</div>');
    $form->addGeneralField('<br />');
    $form->addSubmit( "submit_form3" , "OK" );
    $form->addGeneralField('</fieldset>');
    $form->EndForm();
    // OUTPUT THE FORM.
    echo $form;
}
catch( Form_Builder_Exception $e ) {
        echo '<b>FORM TEMPLATE ERRORS:</b>';
        echo $e->getMessage();
}
catch( Exception $e ) {
        echo $e->getMessage();    
}
?>
