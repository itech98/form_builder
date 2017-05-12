<?php
//
// basic  form 11
//
echo '<body>';
echo '<head>';
echo '<style>';
echo 'label { display: inline-block;  width:180px; }';
echo 'body  { background:#9d9d9d; }';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<h1>basic form 11 -- User Profile</h1>';
// include main class.
include_once('includes/Form_Builder.php');


try  {
    // BUILD NEW FORM.
    $required = array( 'your_name' , 'sel_sex' ,'dd','mm','yy' );
    $fields   = array( 'your_name'=>'string' , 'sel_sex'=>'string', 'dd'=>'numeric','mm'=>'numeric','yy'=>'numeric'  );
    $form = new Form_Builder();
    $form->process_submitted_data( $fields , $required );

    /*
            FORM SUBMITTED?
     */
    if(isset($form->form_data['submit'])) {
                // VALIDATION.
                // 
                // CHECK THE DATE OF BIRTH IS VALID...
                $dob_year  = $form->get_value('yy');
                $dob_month = $form->get_value('mm');
                $dob_day   = $form->get_value('dd');
                // change month to number -- e.g. January=1, February=2 etc..
                $dob_month = date( "n" , strtotime($dob_month) );
                $dob       = $dob_year.'-'.$dob_month.'-'.$dob_day;
                $res       = $form->check_value( 'date'  , $dob );
                if ( $res==false ) {
                    $form->set_error("Please enter valid date!!");
                }
                // CHECK FOR MANDATORY FIELD VALUES.
                $form->validate_mandatory();
                // CHECK FOR VALID ENTRIES -- e.g. field is number etc.
                $form->validate_entries();
                // UPLOAD FILES>
                $upload_dir ='upload/';
                $upfile     ='upload_file';
                $validate_files = new Form_Builder_Files ( $form->form_files , 50000 , $upload_dir , array('jpg','png','jpeg','gif') , $upfile ,"logo" );
                $validate_files->validate_uploaded_files();
                // GET ANY ERRORS AND DISPLAY.....
                $errors = $validate_files->get_errors();
                $form_errors = $form->get_errors();
                if(!empty($errors)){
                    // UPLOAD IMAGE ERRORS.
                    foreach( $errors as $r ) {
                        echo '<font color="red">'.$r.'</font><br />';
                    }
                    // FORM ERRORS
                    foreach( $form_errors as $r ) {
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
                    echo 'OUTPUT::::::';
                    $output = $validate_files->output();
                    foreach ( $output as $o  ) {
                            $uploaded_file_name     = $o['file'];
                            $uploaded_file_type     = $o['type'];
                            $uploaded_file_size     = $o['size'];
                            // output name, type etc...
                            echo "<b>ALTERNATIVE FILE NAME:</b> =   $alternative_file_name <br />";
                            echo "<b>FILE NAME:</b> =    $uploaded_file_name <br />";
                            echo "<b>FILE TYPE:</b> =    $uploaded_file_type <br />";
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
    } 
    
    /*
     * BUILD THE ACTUAL FORM .
     */
    $form->addForm  ( "form11" , "post" , "example_form11.php" , array('enctype'=>"multipart/form-data")  );
    $form->addGeneralField( "<fieldset>" );
    $form->addGeneralField( "<h2>Manage Profile</h2>" );
    
    $form->addLabel ( "Name:" );
    $form->addInput( "your_name" , $form->get_value('your_name') , "text" );    
    $form->addGeneralField('<br />');

    $form->addLabel( "Your Date of Birth" );
    $form->addDate( "dd" , "mm" , "yy" , 1900 ,  date('Y') - 1900  );
    $form->addGeneralField('<br />');

    $form->addLabel("Male or Female ?");
    $opts=array("male" ,  "female" );
    $form->addSelect( "sel_sex" , $form->get_value('sel_sex') ,  $opts );
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
