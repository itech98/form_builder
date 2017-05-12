<?php
# Form_Builder class 
# coded by Ian Sherman ITECH
# e-mail : info@itech123.co.uk 
# year: 2017
spl_autoload_register(function ($class) {
        $directory =  dirname(__FILE__).'/';
        if (file_exists( $directory.$class.'.php' )) {
                require_once  $directory.$class.'.php';
        } else {dir('cannot load class file...');}
});




class   Form_Builder  extends Form_Builder_Validation {
	/*
		string of the end form.
	*/
	public $the_form;
        /*
         *      The form data.
        */
        public $form_data;
        /*
         *      $_FILES if used.
        */
        public $form_files;
        /*
         *      form_method: method used for form - post/get.
        */
        private $form_method;




        /**
         * __construct
         *
         * class constructor
         *
         * @param none
         * @return nothing.
        */
	function __construct($add_captcha=true,$add_security_code=true) {
		//
		// usual form processing -- prior to form submission.
		//
            	$this->captcha=$add_captcha;
                $this->security_code=$add_security_code;
                if (($this->captcha==true) || ($this->security_code==true)) {
                    if (session_id() ==  '') {session_start();}            
                }
	}



        /**
         * __toString
         *
         * used when outputing contents of a instance.
         *
         * @param none.
         * @return string the final finished form.
        */
	public function __toString() {
		$form='';
		if(!is_array($this->the_form)) {die("not array");}

                foreach( $this->the_form as $tf )  {
                        $ff= $tf['final_form'];
			$form.=$ff;
		}


		return  $form;
	}



        /**
         * add_to_tags
         *
         * Add takes a array as formed in AddInput, AddTextArea etc and forms actual form tag.
         *
         * @param array $arr    - array of tag properties.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	private function add_to_tags( $arr = array() , $options=array()  ) {
                if (!is_array($arr))       { throw new Form_Builder_Exception("Form_Builder: {add_to_tags} - $arr     must be a array");    }
                if (!is_array($options))   { throw new Form_Builder_Exception("Form_Builder: {add_to_tags} - $options must be a array");    }

		$tags = new tags( $arr , $options );
                $o = $tags->output();
                
                $this->the_form[] = array('final_form'=>$o );
	}



        /**
         * addForm
         *
         * Add a Form tag.
         *
         * @param string $form_id    - id of form.
         * @param string $method     - generally either POST or GET.
         * @param string $action     - what to do once form is posted.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addForm( $form_id='' , $method='post' , $action='#' , $options = array()  ) {
                if ( $action != '' )    { if (!is_string($action)) { throw new Form_Builder_Exception("Form_Builder: {constructor} - form action must be a string"); } }
                if ( $method != '' )    { if (!is_string($method)) { throw new Form_Builder_Exception("Form_Builder: {constructor} - form method must be a string"); } }
                if ( $form_id != '')    { if (!is_string($form_id)){ throw new Form_Builder_Exception("Form_Builder: {constructor} - form id must be a string"); } }

		$t_array = array( 'tag_type'=>'form' , 'id'=>$form_id , 'method'=>$method  , 'action'=>$action  );
		$this->add_to_tags( $t_array , $options );
                $this->form_method = $method;
	}



        /**
         * addImage
         *
         * Add a Image
         *
         * @param string $img_src   - img src.
         * @param int    $height    - img height.
         * @param int    $width     - img width.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
        public function addImage( $img_src='' , $width=0 , $height=0 , $options=array()  ) {
                if(!is_string($img_src))  { throw new Form_Builder_Exception("image src must be string");    }
                if(!is_numeric($width))   { throw new Form_Builder_Exception("Image Width must be number");  }
                if(!is_numeric($height))  { throw new Form_Builder_Exception("Image Height must be number"); }

                $t_array = array(  'tag_type' => 'image' ,  'src'=>$img_src , 'width'=>$width , 'height'=>$height  );
		$this->add_to_tags( $t_array , $options );                
        }
        
        
        
        /**
         * addLabel
         *
         * Add a label control.
         *
         * @param string $value   - text to show on label.
         * @param string $for     - label for specific field.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addLabel( $value='' , $for='' , $options=array() )  {
                if (!is_string($for))    { throw new Form_Builder_Exception("Form_Builder: {addLabel} - label for must be a string");    }
                if (!is_string($value))  { throw new Form_Builder_Exception("Form_Builder: {addLabel} - value  must be a string");    }

		$t_array = array( 'tag_type' => 'label' , 'value' => $value , 'for' => $for );
		$this->add_to_tags( $t_array , $options );
	}



        /**
         * addInput
         *
         * Add a input box.
         *
         * @param string $id    - id of input.
         * @param string $value - value to show.
         * @param string $type  - type of input -- e.g. text, date, hidden etc.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addInput( $id='' , $value='' , $type='text' , $options=array() ) {
                if (!is_string($id))    { throw new Form_Builder_Exception("Form_Builder: {addInput} - $id must be a string");    }
                if (!is_string($value)) { throw new Form_Builder_Exception("Form_Builder: {addInput} - $value must be a string");    }
                if (!is_string($type))  { throw new Form_Builder_Exception("Form_Builder: {addInput} - $type must be a string");    }

                $t_array = array( 'tag_type' => 'input' , 'value' => $value , 'id' => $id, 'type'=>$type  );
		$this->add_to_tags( $t_array , $options );
	}

        
        
        /**
         * addFileUpload
         *
         * Add a file upload input.
         *
         * @param string $id    - id of image.
         * @param string $src   - image src.
         * @param int    $width - width of image
         * @param int    $height- height of image.
         * @param array  $options - array of additional options.
         * @param string $new_fileName - new filename for uploaded file,
         * @return nothing.
        */
	public function addFileUpload( $id='' , $upload_dir='upload' , $create_if_not_exist=true , $new_fileName='' , $options=array() ) {
                if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $id must be a string");    }
                if (!is_string($upload_dir))            { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $upload_dir must be a string");    }
                if (!is_bool($create_if_not_exist))     { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $create_if_not_exist must be a boolean");    }
                if (!is_string($new_fileName))          { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $new_fileName must be a string");    }
            
                if(!file_exists($upload_dir)) {
                    if($create_if_not_exist==true ) {
                        $dir = mkdir( $upload_dir ,  0750  );
                    } else { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - file upload cannot upload files to $upload_dir"); }
                }
            
		$t_array = array( 'tag_type'=> 'file' , 'id'=> 'upload_file' , 'upload_dir'=>$upload_dir , 'create_if_not_exist'=>$create_if_not_exist  );
		$this->add_to_tags( $t_array , $options );
	}

        
        
	public function addMultipleFileUploads( $id='' , $upload_dir='upload' , $create_if_not_exist=true , $options=array() ) {
                if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $id must be a string");    }
                if (!is_string($upload_dir))            { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $upload_dir must be a string");    }
                if (!is_bool($create_if_not_exist))     { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - $create_if_not_exist must be a boolean");    }
            
                if(!file_exists($upload_dir)) {
                    if($create_if_not_exist==true ) {
                        $dir = mkdir( $upload_dir ,  0750  );
                    } else { throw new Form_Builder_Exception("Form_Builder: {addFileUpload} - file upload cannot upload files to $upload_dir"); }
                }
            
		$t_array = array( 'tag_type'=> 'file' , 'id'=> 'upload_file[]' , 'upload_dir'=>$upload_dir , 'create_if_not_exist'=>$create_if_not_exist  );
		$this->add_to_tags( $t_array , $options );
	}


        /**
         * addSelect
         *
         * Add a select list.
         *
         * @param string $id    - id of select list.
         * @param array  $select_list_options - list of select options.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addSelect( $id='' , $value ='' ,  $select_list_options=array() ,  $options=array()  ) {
                if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addSelect} - $id must be a string");    }
                if (!is_array($select_list_options))    { throw new Form_Builder_Exception("Form_Builder: {addSelect} - $select_list_options must be a array");    }

		$t_array = array( 'tag_type'=> 'select' , 'id'=>$id , 'value' => $value,  'select_options'=>$select_list_options  );
		$this->add_to_tags( $t_array , $options  );
	}



        /**
         * addSubmit
         *
         * Add a submit button.
         * NOTE: Also sets the property submit_button as there may be multiple forms.
         *
         * @param string $id    - id of submit button.
         * @param string $value - value to show.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addSubmit( $id='' , $value='' , $options=array()  ) {
                if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addSubmit} - $id must be a string");    }
                if (!is_string($value))                 { throw new Form_Builder_Exception("Form_Builder: {addSubmit} - $value must be a string");    }
            
		$t_array = array( 'tag_type'=>'submit', 'type'=>'submit', 'id'=> 'submit'  , 'value'=>$value  );
		$this->add_to_tags( $t_array , $options );
	}



        /**
         * addCheckBox
         *
         * Add a check box.
         *
         * @param string $id    - id of checkbox.
         * @param string $value - value to show.
         * @param array  $options - array of additional options.
         * @return nothing.
        */        
	public function addCheckBox( $id='' , $value='' , $options=array() ) {
                if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addCheckBox} - $id must be a string");    }
                if (!is_string($value))                 { throw new Form_Builder_Exception("Form_Builder: {addCheckBox} - $value must be a string");    }

		$t_array = array( 'tag_type'=>'checkbox' , 'id'=>$id , 'name'=>$id , 'value'=>$value);
		$this->add_to_tags( $t_array , $options );
	}



        /**
         * addRadio
         *
         * Add a radion button.
         *
         * @param string $id    - id of radion button.
         * @param string $value - value to show.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addRadio( $id='' , $value ='' , $options=array() ) {
                if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addRadio} - $id must be a string");    }
                if (!is_string($value))                 { throw new Form_Builder_Exception("Form_Builder: {addRadio} - $value must be a string");    }
            
		$t_array = array( 'tag_type'=>'radio' , 'id'=>$id  , 'name'=>$id ,  'value'=>$value );
		$this->add_to_tags( $t_array , $options );
	}

        
        
        /**
         * addTextArea
         *
         * Add a textare to the form.
         *
         * @param string $id      - ID of textarea.
         * @param string $value   - value to show in text area.
         * @param int    $rows    - number of textarea rows.
         * @param int    $cols    - number of textarea columns.
         * @param array  $options - array of additional options.
         * @return nothing.
        */
	public function addTextArea( $id='' , $value='' , $rows=0 , $cols=0 , $options=array() ) {
            if ( $rows != '0' )  { if ((!is_string($rows)) && (!is_numeric($rows)))    { throw new Form_Builder_Exception("Form_Builder: {addTextArea} - textarea rows must be a number/string"); } }
            if ( $cols != '0' )  { if ((!is_string($cols)) && (!is_numeric($cols)))    { throw new Form_Builder_Exception("Form_Builder: {addTextArea} - textarea cols must be a number/string"); } }
            if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addTextArea} - $id must be a string");    }
            if (!is_string($value))                 { throw new Form_Builder_Exception("Form_Builder: {addTextArea} - $value must be a string");    }
            
            $t_array = array( 'tag_type'=>'textarea' , 'id'=>$id , 'name'=>$id , 'value'=>$value , 'rows'=>$rows , 'cols'=>$cols   );
            $this->add_to_tags( $t_array , $options );
	}



        /**
         * addButton
         *
         * Add a button to the form.
         *
         * @param string $id      - form ID.
         * @param string $value   - text to display on the button.
         * @param array  $options - list of additional options.
         * @return nothing.
        */
        public function addButton( $id='' , $value='' , $options=array() ) {
            if (!is_string($id))                    { throw new Form_Builder_Exception("Form_Builder: {addButton} - $id must be a string");    }
            if (!is_string($value))                 { throw new Form_Builder_Exception("Form_Builder: {addButton} - $value must be a string");    }

            $t_array = array( 'tag_type'=>'button' , 'id'=>$id , 'name'=>$id , 'value'=>$value ,   );
            $this->add_to_tags( $t_array , $options );
        }
   
        
        
        /**
         * addGeneralField
         *
         * Add a general form field to form - e.g. <br /> or <table>
         *
         * @param $tag_type -- the string for a HTML element e.g.addGeneralField('<br />');
         * @param $options array of extra options.
         * @return none returns nothing.
        */
	public function addGeneralField( $tag_type='' , $options=array()  ) {
            if (!is_string($tag_type))                    { throw new Form_Builder_Exception("Form_Builder: {addGeneralField} - $tag_type must be a string");    }

            $t_array = array( 'tag_type'=> $tag_type );
            $this->add_to_tags( $t_array , $options );	
        }



        /**
         * endForm
         *
         * Add a </form> to complete the form.
         *
        */        
	public function endform() {
		$this->add_to_tags( array( 'tag_type'=>'endform') );
	}
        
        
        
        /**
         * addDate
         *
         * Add a date selection using select lists.
         *
         * @param string $dayId   - ID of day of date.
         * @param string $monthId - ID of month of date.
         * @param string $yearId  - ID of year of date.
         * @param int $yearStart  - year start .
         * @param int $numYears   - Number of years to show.
         * @return nothing.
        */
        public function addDate( $dayId='' , $monthId='', $yearId='' , $yearStart=0 , $numYears=10 ) {
            if (!is_string($dayId))       { throw new Form_Builder_Exception("Form_Builder: {addDate} - $dayId must be a string");    }
            if (!is_string($monthId))     { throw new Form_Builder_Exception("Form_Builder: {addDate} - $monthId must be a string");    }
            if (!is_string($yearId))      { throw new Form_Builder_Exception("Form_Builder: {addDate} - $yearId must be a string");    }
            if (!is_numeric($yearStart))  { throw new Form_Builder_Exception("Form_Builder: {addDate} - $yearStart must be a number");    }
            if (!is_numeric($numYears))   { throw new Form_Builder_Exception("Form_Builder: {addDate} - $numYears must be a number");    }

            if (isset($this->form_data[$dayId]))   { $dd_value = $this->get_value( $dayId );   }   else {$dd_value='';}
            if (isset($this->form_data[$monthId])) { $mm_value = $this->get_value( $monthId ); }   else {$mm_value='';}
            if (isset($this->form_data[$yearId]))  { $yy_value = $this->get_value( $yearId );  }   else {$yy_value='';}
            // day.
            for ($d=1;$d<=31;$d++) { $day[]=$d; }
            $this->addSelect( $dayId    , $dd_value , $day );
            $months =  array( 'January','February','March','April','May','June','July','August','September','October','November','December');
            $this->addSelect( $monthId  , $mm_value , $months );
            for ( $y=$yearStart; $y<=$yearStart+$numYears; $y++ ) { $years[]=$y; }
            $this->addSelect( $yearId   , $yy_value , $years );
        }

        

        /**
         * addCaptcha
         *
         * generate a captcha image in captchaImage() function
         * and add it and a label and input to the form
         *
         * @param string $image_tags_start      - tag(s) to surround captcha image e.g. <div> or <p>
         * @param string $image_tags_end        - tag(s) to end captcha image surround e.g. </div> or </p> etc
         * @param string $captcha_text_start    - tag(s) to surround captcha text.
         * @param string $captcha_text_end      - tag(s) to end text surround.
         * @param string $image_text            - the actual text to display - if required - e.g. "Enter captcha image text"....
         * @param string $input_tags_start      - tag(s) to surround captcha input box 
         * @param string $input_tags_end        - tag(s) to end captcha input box.
         * @return none.
        */
	function addCaptcha( $image_tags_start='',$image_tags_end='',$captcha_text_start='',$captcha_text_end='', $input_tags_start='',$input_tags_end='', $image_text=''  )  {
                if (!is_string($image_tags_start))    { throw new Form_Builder_Exception("Form_Builder: {addCaptcha} - $image_tags_start  must be a string");    }
                if (!is_string($image_tags_end))      { throw new Form_Builder_Exception("Form_Builder: {addCaptcha} - $image_tags_end  must be a string");    }
                if (!is_string($captcha_text_start )) { throw new Form_Builder_Exception("Form_Builder: {addCaptcha} - $captcha_text_start  must be a string");    }
                if (!is_string($captcha_text_end ))   { throw new Form_Builder_Exception("Form_Builder: {addCaptcha} - $captcha_text_end  must be a string");    }
                // check if captcha flag is true -- exit if not.
                if($this->captcha==false) {return;}
                // generate the new captcha image...
                $i = $this->captchaImage();
                $this->addGeneralField($image_tags_start);
                $this->addImage( $i , "130" , "60" );
                $this->addGeneralField($image_tags_end);
                if($image_text!='') {
                    $this->addGeneralField($captcha_text_start);
                    $this->addLabel( $image_text );
                    $this->addGeneralField($captcha_text_end);
                }
                $this->addGeneralField( $input_tags_start );                
                $this->addInput( 'captcha' , "" , 'text'  );
                $this->addGeneralField( $input_tags_end );
	}



        /**
         * captchaImage
         *
         * generate a captcha image.
         *
         * @param none.
         * @return image - the generated image.
        */
	private function captchaImage() {
                $current_dir =  dirname(__FILE__) . '/';
                $font_file  =  $current_dir . 'SHADSER.TTF';
                if (!file_exists($font_file)) { throw new Form_Builder_Exception("Form_Builder: {captchaImage} - captcha font file not found!!"); }

		// new image 130x50.
		$myImage = imagecreate(130, 50);
		$code=rand(1000,9999);
		// allocate colours for the image.
		$myGray =   imagecolorallocate($myImage, 204, 204, 204);
		$myBlack =  imagecolorallocate($myImage, 0, 0, 0);
		// line across image.
		imageline($myImage, 1, 3, 120, 60, $myBlack);
		imageline($myImage, 20 , 3, 140, 60, $myBlack);
		// use font file.
		//$font = 'AHGBold.ttf';
		$font = $font_file;
		imagettftext($myImage, 30, 0, 3, 40 , $myBlack, $font, $code );
		ob_start();
		imagepng($myImage);
		$ii = '';
		$ii .=  'data:image/jpeg;base64,'.base64_encode(ob_get_clean() );
		imagedestroy($myImage);
                //
                // SET SESSION
                //
                $_SESSION["captcha_code"]=$code;

                
		return $ii;
	}



	function addTag($tag) { $this->the_form .= $tag; }



        /**
         * process_submitted_data
         *
         * process submitted data and files.
         *
         * @param array $fields                 - an array of fields to be validated.
         * @param array $required               - an array of required (mandatory) fields.
         * @param boolean $allow_empty_values   - if true allow empty values .false must be validated
         * @return none.
        */
	public function process_submitted_data( $fields,$required,$allow_empty_values=false ) {
                if (!is_array($fields))             { throw new Form_Builder_Exception("Form_Builder: {process_submitted_data} - $fields  must be a array");    }
                if (!is_array($required))           { throw new Form_Builder_Exception("Form_Builder: {process_submitted_data} - $required  must be a array");    }
                if (!is_bool($allow_empty_values))  { throw new Form_Builder_Exception("Form_Builder: {process_submitted_data} - $allow_empty_values  must be a boolean");    }
		$this->validation_fields    =   $fields;
		$this->validation_required  =   $required;
                $this->allow_empty          =   $allow_empty_values;
                //
		// determine if form is using $_POST, $_GET...
                //
		switch(strtolower($this->form_method))  {
                        case 'post'     :   $this->form_data = $_POST;          break;
                        case 'get'      :   $this->form_data = $_GET;           break;
                        case 'request'  :   $this->form_data = $_REQUEST;       break;
			default:            $this->form_data = $_POST;          break;
		}
                //
                // handle file uploads.
                //                
                if(!empty($_FILES)) {
                    $this->form_files = $_FILES;
                }
                // call validation (parent) constructor.
       		parent::__construct( $this->validation_fields , $this->validation_required , $this->form_data , $this->form_files );
 	}



       /**
         * generateKey
         *
         * generate a security form key.
         *
         * @param none.
         * @return string encrypted string.
        */
	private function generateKey()  {
		//Get the IP-address of the user
		$ip = $_SERVER['REMOTE_ADDR'];
		//We use mt_rand() instead of rand() because it is better for generating random numbers. We use 'true' to get a longer string.
		$uniqid = uniqid(mt_rand(), true);
		//Return the hash
		return md5($ip . $uniqid);
	}



       /**
         * addSecurityCode
         *
         * Function to output the form key
         *
         * @param none.
         * @return none.
        */
	public function addSecurityCode() {
                // check if the security_code flag is true-- exit if not.
                if($this->security_code==false) {return;}
		//Generate the key and store it inside the class
		$key  = $this->generateKey();
		$_SESSION['form_key']=$key;
		//Output the form key
                $this->addInput( 'form_key' , $key , 'hidden'  );
	}



        
	public function get_field_values() {
		return $this->form_data;
	}



        /**
         * get_value
         *
         * Function to get the POSTED/GET value of a field.
         *
         * @param string $field = the field to get value of.
         * @return the value if found.
        */
        public function get_value( $field='' ) {
            if (!is_string($field)) { throw new Form_Builder_Exception("Form_Builder: {get_value} - $field  must be a string");    }

            $val='';
            if($field=='') { return $val; }
            if(isset($this->form_data[$field])) { $val=$this->form_data[$field]; }
            return $val;
        }
        


        // TAKEN FROM:
function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
    // hash
    $key = hash('sha256', $secret_key);    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
        
        
        
// ##### END OF CLASS #####
}

?>
