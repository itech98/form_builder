<?php
//
// basic contact form
//
echo '<h1>form 5 -- Example custom form</h1>';
// include main class.
include_once('Form_Builder.php');
echo 'Note - in validation we allow empty values so not ALL times need to be entered. ';
echo 'in process_submitted_data this is the 3rd parameter - e.g.:';
echo '$form->process_submitted_data ( $validation , array() ,  true );';


try  {
    $form = new Form_Builder( true , true );
    $validation = array ( 'sunday0' => 'time' ,'monday0' => 'time' ,'tuesday0' => 'time' , 'wednesday0' => 'time' ,'thursday0' => 'time' ,'friday0' => 'time' ,'saturday0' => 'time' ,'sunday1' => 'time' ,'monday1' => 'time' ,'tuesday1' => 'time' ,'wednesday1' => 'time' ,'thursday1' => 'time' ,'friday1' => 'time' ,'saturday1' => 'time' ,'sunday2' => 'time' ,'monday2' => 'time' ,'tuesday2' => 'time' ,'wednesday2' => 'time' ,'thursday2' => 'time' ,'friday2' => 'time' ,'saturday2' => 'time' ,'sunday3' => 'time' ,'monday3' => 'time' ,'tuesday3' => 'time' ,'wednesday3' => 'time' ,'thursday3' => 'time' ,'friday3' => 'time' ,'saturday3' => 'time' );    
    $form->process_submitted_data ( $validation , array() ,  true );
    // FORM SUBMITTED?
    if(isset($form->form_data['submit'])) {
                // VALIDATION.
                // CHECK FOR VALID ENTRIES -- e.g. field is number etc.
                $form->validate_entries();
                // CLEAN THE DATA...
                $form_data  = $form->clean_data($form->submitted_data);

                // GET ANY ERRORS AND DISPLAY.....
                $errors = $form->get_errors();
                if(!empty($errors)){
                    echo '<br /><br />';
                    foreach( $errors as $r ) {
                        echo '<font color="red">'.$r.'</font><br />';
                    }
                    echo '<br />';
                } else {
                    // DATA OK SO OUTPUT DATA....
                    echo '<h3>FORM DATA</h3>';
                    foreach ($form->form_data as $key=>$val ) {
                        echo "$key = $val <br />";
                    }
                    exit;
                }
    } 
    $form->addForm  ( "form5" , "post" , "example_form5.php"  );
    $days=array( 'sunday','monday','tuesday','wednesday','thursday','friday','saturday' );
    $form->addLabel('Film: Batman Returns -- <b>Add Film Times - e.g. 09:00</b><br /><br />');
    $form->addGeneralField('<table>');
    $form->addGeneralField('<tr><td></td>');
    foreach ( $days as $d ) {
            $form->addGeneralField('<td>'.$d.'</td>');
    }
    $form->addGeneralField('</tr>');
    $c   = 0;
    for ($j=0; $j<=3; $j++ ) {
        $form->addGeneralField('<tr>');
        $form->addGeneralField('<td>TIME:</td>');
        for ( $i=0; $i<=6; $i++ ) {
                    $form->addGeneralField('<td>');
                    $id = $days[$i].$c;
                    $val = $form->get_value( $id );
                    $form->addInput( $id  , $val , 'text' ,array('size'=>"5" ));
                    $form->addGeneralField('</td>');
        }
        $form->addGeneralField('</tr>');
        $c++;
    }
    $form->addGeneralField( '</table>');        
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
