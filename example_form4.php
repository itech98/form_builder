<?php
//
// basic contact form
//
echo '<h1>basic form 4 -- Example using all available form fields.</h1>';
// include main class.
include_once('includes/Form_Builder.php');


try  {
    $form = new Form_Builder();
    $form->addForm  ( "form4" , "post" , "example_form4.php"  );

    // INPUT
    $form->addGeneralField( "<fieldset>" );
    $form->addLabel ( "Enter name" );
    $form->addInput ( "name" ,  "" , "text" );
    $form->addGeneralField('<br />');

    // RADIO
    $form->addGeneralField( "<p>" );
    $form->addImage( 'img/female.png' , 60 , 60 );
    $form->addRadio('female');
    $form->addImage( 'img/male.png' , 60 , 60 );
    $form->addRadio('male');
    $form->addGeneralField('</p>');
    
    // LABEL
    $form->addGeneralField( "<p>" );
    $form->addLabel("Example Label");
    $form->addGeneralField('</p>');
    
    // FILE UPLOAD.
    $form->addGeneralField( "<p>" );
    $form->addFileUpload( 'fileup' , 'upload/' , true );
    $form->addGeneralField('</p>');

    // SELECT LIST.
    $list=array( 'hamburgers' , 'fries' , 'cheeseburger' , 'ribs' );
    $form->addGeneralField( "<p>" );
    $form->addSelect( 'sel1' , "" , $list );
    $form->addGeneralField( "</p>" );
    
    // CHECKBOX
    $form->addGeneralField( '<p>' );
    $form->addCheckBox( 'cbox1' , "FREE BEER"  );
    $form->addGeneralField('</p>');

    // TEXTAREA.
    $form->addGeneralField( '<p>' );    
    $form->addTextArea(  'txta1' , '' , 10 , 50 );
    $form->addGeneralField( '</p>' );

    // BUTTON.
    $form->addGeneralField( '<p>' );        
    $form->addButton( 'btn1' , 'Self Destruct' );
    $form->addGeneralField( '</p>' );
    
    // GENERAL FIELD
    $form->addGeneralField( '<br />' );

    // PHP Date - using selects.
    $form->addGeneralField( '<p>' );
    $form->addDate( 'dd1' , 'mm1', 'yy1' , 2017 , 2030 );
    $form->addGeneralField( '</p>' );        

    $form->addGeneralField('<p>New Date type below -- this is NOT supported by ALL Browsers</p>');
    $form->addGeneralField( '<p>' );
    $form->addInput('datenew', '');
    $form->addGeneralField( '</p>' );        

    // CAPTCHA
    $form->addGeneralField( '<p>' );
    $form->addCaptcha();
    $form->addGeneralField( '</p>' );        


         

    // SUBMIT
    $form->addSubmit('btn' , 'OK');
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
