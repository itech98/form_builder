<?php
//
// basic form
//

echo '<h1>basic form 1 -- One field + Submit Button</h1>';
// include main class.
include_once('includes/Form_Builder.php');
try  {
    // build new form...
    $form = new Form_Builder();
    $form->addForm  ( "form1" , "post" , "example_form1.php" );

    $form->addGeneralField( "<fieldset>" );
    $form->addLabel ( "Enter name" );
    $form->addInput ( "name" ,  "" , "text" );
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
    
}
?>
