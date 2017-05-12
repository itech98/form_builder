<?php
//
// basic contact form
//
echo '<h1>basic form 6 -- Upload a Image.</h1>';
// include main class.
include_once('includes/Form_Builder.php');


try  {
    // BUILD NEW FORM.
    $required = array( 'image_description' );
    $fields   = array( 'image_description'=>'string' );
    $form = new Form_Builder();
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
                $validate_files = new Form_Builder_Files ( $form->form_files , 500000 , $upload_dir , array('jpg','png','jpeg','gif') , $upfile ,"logo" );
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
      
                    // is the last parameter set to use alternative name?
                    $alternative_file_name = $validate_files->get_file_name();

                    // get uploaded file info....
                    $uploaded_file_name     = $validate_files->validation_files['upload_file']['name'];                    
                    $uploaded_file_type     = $validate_files->validation_files['upload_file']['type'];
                    $uploaded_file_tmpname  = $validate_files->validation_files['upload_file']['tmp_name'];
                    $uploaded_file_size     = $validate_files->validation_files['upload_file']['size'];
                    // output name, type etc...                    
                    echo "<b>ALTERNATIVE FILE NAME:</b> =   $alternative_file_name <br />";
                    echo "<b>FILE NAME:</b> =    $uploaded_file_name <br />";
                    echo "<b>FILE TYPE:</b> =    $uploaded_file_type <br />";
                    echo "<b>FILE TMPNAME:</b> = $uploaded_file_tmpname <br />";
                    echo "<b>FILE SIZE:</b> =    $uploaded_file_size <br />";
                    // are we using alternative filename ?
                    if($alternative_file_name!='') {
                        $type = $uploaded_file_type;
                        if($type == "image/gif")  { $ext=".gif";}
                        if($type == "image/png")  { $ext=".png";}
                        if($type == "image/jpeg") { $ext=".jpg";}
                        $img = $upload_dir.$alternative_file_name.$ext;
                    } else {
                        // no alternative -- use actual filename...
                        $img = $upload_dir.$uploaded_file_name;
                    }
                    // show the image on the screen.
                    echo '<img src="'.$img.'" />';
                    echo '<br />';
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
