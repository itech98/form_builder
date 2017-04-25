<?php
# Form_Builder_Files class 
# coded by Ian Sherman ITECH
# e-mail : info@itech123.co.uk 
# year: 2017



class Form_Builder_Files {
    private $validation_errors=array();
    private $max_file_size;
    private $file_dir;
    private $file_types;
    private $file_name;
    public  $validation_files;
    private $error_messages;


    
    public function __construct( $files=array() , $file_size =20000 , $file_dir='uploads/' , $file_types=array() , $file_id='image' ) {
            if (!is_array( $files ))        { throw new Form_Builder_Exception("Form_Builder_Files: {constructor} - $files       must be a array");    }
            if (!is_numeric( $file_size ))  { throw new Form_Builder_Exception("Form_Builder_Files: {constructor} - $file_size       must be a number");    }
            if (!is_string( $file_dir ))    { throw new Form_Builder_Exception("Form_Builder_Files: {constructor} - $file_dir       must be a string");    }
            if (!is_array( $file_types ))   { throw new Form_Builder_Exception("Form_Builder_Files: {constructor} - $file_types       must be a array");    }
            if (!is_string( $file_id ))     { throw new Form_Builder_Exception("Form_Builder_Files: {constructor} - $file_id       must be a string");    }
        
            $this->max_file_size    =   $file_size;
            $this->file_dir         =   $file_dir;
            $this->file_types       =   $file_types;
            $this->file_name        =   $file_id;
            $this->validation_files =   $files;
            $this->error_messages       =   array(
                                        'file_exists'       =>  'Sorry, file already exists.',
                                        'file_too_large'    =>  'Sorry, your file is too large',
                                        'file_wrong_type'   =>  'Sorry, only JPG, JPEG, PNG & GIF files are allowed.',
                                        'file_not_uploaded' =>  'Sorry, your file was not uploaded',
                                        'file_uploaded'     =>  'your file has been uploaded!!',
                                        'file_error'        =>  'Sorry, there was an error uploading your file.',
             );
    }
    
    
    
    /**
         * validate_uploaded_files
         *
         * checks the uploaded file(s) are ok.
         * 
         * @param none.
         * @return true/false -- true means captcha entered is OK , false means it is wrong.
    */
    public function validate_uploaded_files() {
            //
            // FILES ARRAY EMPTY -- CAN BE IF USER Forgets ENCTYPE.
            //
            if (empty($this->validation_files)) {
                    $e=$this->error_messages['file_error'];
                    $this->validation_errors[] = $e;
                    return;
            }
            //
            // PROCESS FILES.
            //
            $target_file  =   $this->file_dir.basename($this->validation_files[$this->file_name]["name"]);
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $uploadOk=1;
            // Check if file already exists
            if (file_exists( $target_file )) {
                $e=$this->error_messages['file_exists'];
                $this->validation_errors[] = $e;
                $uploadOk = 0;
            }
            // Check file size
            if ($this->validation_files [ $this->file_name ]["size"] > $this->max_file_size  ) {
                $e=$this->error_messages['file_too_large'];
                $this->validation_errors[] = $e;
                $uploadOk = 0;
                }
            // Allow certain file formats
            if (!in_array( $imageFileType , $this->file_types ) ) {
                $e=$this->error_messages['file_wrong_type'];
                $this->validation_errors[] = $e;
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $e=$this->error_messages['file_not_uploaded'];
                $this->validation_errors[] = $e;
                // if everything is ok, try to upload file
            } else {
                    if (move_uploaded_file( $this->validation_files[ $this->file_name]["tmp_name"], $target_file)) {
                            $ok = $this->error_messages['file_uploaded'];
                            $this->validation_OK[] = $ok;
                    } else {
                            $e=$this->error_messages['file_error'];
                            $this->validation_errors[] = $e;
                    }
            }
            //echo $this->validation_files[$this->file_name]['error'];
      }
    


          public function validate_uploaded_multifiles() {
            //
            // FILES ARRAY EMPTY -- CAN BE IF USER Forgets ENCTYPE.
            //
            if (empty($this->validation_files)) {
                    $e=$this->error_messages['file_error'];
                    $this->validation_errors[] = $e;
                    return;
            }
            //
            // PROCESS FILES.
            //
            $total = count( $this->validation_files[$this->file_name]["name"] );
            // Loop through each file
            for($i=0; $i<$total; $i++) {
                $target_file  =   $this->file_dir.basename($this->validation_files[$this->file_name]["name"][$i] );
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                $uploadOk=1;
                // Check if file already exists
                if (file_exists( $target_file )) {
                    $e=$this->error_messages['file_exists'];
                    $this->validation_errors[] = $e;
                    $uploadOk = 0;
                }
                // Check file size
                if ($this->validation_files [ $this->file_name ]["size"][$i] > $this->max_file_size  ) {
                    $e=$this->error_messages['file_too_large'];
                    $this->validation_errors[] = $e;
                    $uploadOk = 0;
                    }
                // Allow certain file formats
                if (!in_array( $imageFileType , $this->file_types ) ) {
                    $e=$this->error_messages['file_wrong_type'];
                    $this->validation_errors[] = $e;
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $e=$this->error_messages['file_not_uploaded'];
                    $this->validation_errors[] = $e;
                    // if everything is ok, try to upload file
                } else {
                        if (move_uploaded_file( $this->validation_files[ $this->file_name]["tmp_name"][$i], $target_file)) {
                                $ok = $this->error_messages['file_uploaded'];
                                $this->validation_OK[] = $ok;
                        } else {
                                $e=$this->error_messages['file_error'];
                                $this->validation_errors[] = $e;
                        }
                }
            }
      }

      
      
        /**
         * get_errors
         *
         * checks the captcha value entered matches the one displayed.
         * 
         * @return the contents of private propery validation_errors().
        */
        public function get_errors() {
                    $return_data = $this->validation_errors;


                    return $return_data;
        }

        
        
    
}