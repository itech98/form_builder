<?php
//
// basic contact form
//
echo '<h1>basic form 6 -- Upload a Image.</h1>';
// include main class.
include_once('Form_Builder.php');


try  {
    // BUILD NEW FORM.
    $required = array( 'image_description' );
    $fields   = array( 'image_description'=>'string' );
    $form = new Form_Builder( true , true );
    $form->process_submitted_data( $fields , $required );
    // FORM SUBMITTED?
    if(isset($form->form_data['submit'])) {
                // VALIDATION.
                // CHECK FOR MANDATORY FIELD VALUES.
                $form->validate_mandatory();
                // CHECK FOR VALID ENTRIES -- e.g. field is number etc.
                $form->validate_entries();
                // UPLOAD FILES>
                $upload_dir ='upload/';
                $upfile     ='upload_file';
                $validate_files = new Form_Builder_Files ( $form->form_files , 50000 , $upload_dir , array('jpg','png','jpeg','gif') , $upfile );
                $validate_files->validate_uploaded_files();
                // GET ANY ERRORS AND DISPLAY.....
                $errors = $validate_files->get_errors();
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
              
                    foreach ( $validate_files->validation_files as $v) {
                        foreach($v as $key=>$val ) {
                            echo "<b>$key</b> = $val <br />";
                            if($key=='name') {
                                echo '<img src="'.$upload_dir.$v['name'].'" />';
                                echo '<br />';
                            }
                        }
                    }
                    exit;
                }
    } 
    $form->addForm  ( "form6" , "post" , "example_form6.php" , array('enctype'=>"multipart/form-data")  );
    $form->addGeneralField( "<fieldset>" );
    $form->addLabel ( "<h2>Upload Image</h2>" );
    $form->addLabel ( "<b>NOTE the 4th parameter used in uploads - enctype must be set!!</b><br />" );
    $form->addLabel ( 'E.G.:  $form->addForm  ( "form6" , "post" , "example_form6.php" , array("enctype"=>"multipart/form-data") <br /> ');    
    $form->addLabel ( "enter image description:" );
    $form->addTextArea( "image_description" , "" , 6 , 30 );
    $form->addGeneralField('<br />');
    $form->addFileUpload( 'new_image' , 'upload/' , true );
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
