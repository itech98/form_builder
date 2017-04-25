<?php
//
// basic form
//

echo '<h1>basic form 2 -- 2 fields with Validation</h1>';
echo '<h3>Form with Name, Age -- asks for + validates:</h3>';
echo '      <p>1. Generates a form with Name+Age fields</p>';
echo '      <p>2. that the Name+Age are entered</p>';
echo '      <p>3. that the Name+Age are a string and number</p>';
echo '      <p>4. that the Age is between 18 and 30</p>';
// include main class.
include_once('Form_Builder.php');
try  {
    // BUILD NEW FORM.
    $form = new Form_Builder();
    // BUILD NEW FORM.
    $required = array( 'name' , 'age'  );
    $fields   = array( 'name'=>'string' , 'age'=>'number' );
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
//                $form->validate_captcha();
                // CLEAN THE DATA...
  //              $form->form_data  = $form->clean_data($form->form_data);

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
    $form->addForm  ( "form2" , "post" , "example_form2.php" );
    $form->addGeneralField( "<fieldset>" );
    $form->addLabel ( "Enter name" );
    $form->addInput ( "name" ,  "" , "text" );
    $form->addGeneralField('<br />');
    $form->addLabel ( "Age" );
    $form->addInput( "age");
    $form->addGeneralField('<br />');
    $form->addSubmit( "submit" , "OK" );
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
