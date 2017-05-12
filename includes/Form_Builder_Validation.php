<?php
# Form_Builder_Validation class 
# coded by Ian Sherman ITECH
# e-mail : info@itech123.co.uk 
# year: 2017
spl_autoload_register(function ($class) {
        $directory =  dirname(__FILE__).'/';
        if (file_exists( $directory.$class.'.php' )) {
                require_once  $directory.$class.'.php';
        } else {dir('cannot load class file...');}
});



class Form_Builder_Validation  {
        /*
                    add a captcha.
        */
        protected $captcha;
        /*
                    true/false whether or not to add a security hash to the form -- and check it in validation.
        */
        protected $security_code;    
        /*
         * $validation_fields:          associative array of fields to check- e.g. array('your_email'=>'email');
         */
        private $validation_fields;
        /*
         * $validation_mandatory:       list of mandatory(required) fields in array.
         */
        private $validation_mandatory;
        /*
         * $validation_errors:          array of errors.
        */
        private $validation_errors;
        /*
        * $validation_OK:       data deemed to be OK and any messages..
        */
        private $validation_OK;    
        /*
         * $validation_files
         */
        private $validation_files;
        /*
         * $error_messages: errors shown after validation.
         */
        private $error_messages;
        /*
         * submitted_data : ALL posted data.
         */
        public $submitted_data;
        /*
         * allow empty values
         */
        public $allow_empty;

     


        public function __construct( $fields=array() , $mandatory=array() , $form_fields =array() , $form_files=array(), $allow_empty_values=false  ) {
            if (!is_array( $fields ))           { throw new Form_Builder_Exception("Form_Builder_Validation: {constructor} - $fields       must be a array");    }
            if (!is_array( $mandatory ))        { throw new Form_Builder_Exception("Form_Builder_Validation: {constructor} - $mandatory    must be a array");    }
            if (!is_array( $form_fields ))      { throw new Form_Builder_Exception("Form_Builder_Validation: {constructor} - $form_fields  must be a array");    }
            if (!is_bool($allow_empty_values))  { throw new Form_Builder_Exception("Form_Builder_Validation: {constructor} - $allow_empty_values  must be a boolean");    }
            /*
             * ALL Submitted data.
             */
            $this->submitted_data = $form_fields;
            /*
             * validation_fields: array to check - e.g. 'user_email'=>'email','age'=>'number' ...
             */
            $this->validation_fields    =   $fields;
            /*
             * validation_mandatory: array of mandatory fields to check.
             */
            $this->validation_mandatory =   $mandatory;
            /*
             * validation_errors: array of any errors found in form.
             */
            $this->validation_files     =   $form_files;
            /*
             * validation_errors: array of any errors found in form.
             */
            $this->validation_errors    =   array();
            /*
             * error_messages: complete list of ALL errors output to validation_errors array.
             */
            $this->error_messages       =   array(  'required_field'    =>  'required field [@field] not present!!',
                                                    'field_not_there'   =>  'field [@field] not present',
                                                    'valid_entry'       =>  '[@field] must be a [@value] !!',
                                                    'field_range'       =>  '[@field] not in range of [@value]',
                                                    'field_between'     =>  '[@field] is not between [@value1] and [@value2]',
                                                    'captcha_invalid'   =>  'Captcha Field is invalid',
                                                    'form_invalid'      =>  'Sorry cannot process form'
                                            );
        }



        /**
         *  set_error.
         *
         *  add a error of type '$error' to the errors array!!
         *
         * @param string  $error     - error element to add
         * @return none
        */
        public function set_error( $error='UNKNOWN ERROR', $val=array()  ){
                // search for $error message in the error messages array...
                $e =  array_key_exists ( $error , $this->error_messages );
                if($e==false ) {
                    // error message not found so just add it to error messages array...custom error message.
                    $this->validation_errors[] = $error;
                } else {
                    // get the error message from array....
                    $err_mess = $this->error_messages[ $error ];
                    // set error fields+values.
                    if(is_array($val)) {
                        foreach ( $val  as $key => $value) {
				$tagToReplace = "[@$key]";
				$err_mess  =  str_replace( $tagToReplace , $value , $err_mess );
			}
                        $this->validation_errors[] = $err_mess;
                    }
                }
        }


    
        /**
         * get_errors
         *
         * checks the captcha value entered matches the one displayed.
         * 
         * @param string $return_type       - type of data to return .. array..xml..json.
         * @return the contents of private propery validation_errors().
        */
        public function get_errors( $return_type = 'array' ) {
            if (!is_string($return_type))  { throw new Form_Builder_Exception("Form_Builder_Validation: {get_errors} - $return_type  must be a string");    }            
            switch ( $return_type ) {
                case 'array':    $return_data = $this->validation_errors;               break;
                case 'json':     
                                 $return_data = json_encode($this->validation_errors);
                                 break;
                case 'xml':      
                                 $xml = new SimpleXMLElement('<rootTag/>');
                                 $this->to_xml( $xml , $this->validation_errors );
                                 $return_data =  $xml->asXML();
                                 break;
                default:         $return_data=$this->validation_errors;                 break;
            }


            return $return_data;
        }


    
    /**
         * validate_mandatory.
         *
         * is there a value in the form field
         * if not throw a error. 
         * Also check for data in $_POST/$_GET not expected. 
         *
         * @param string $id    - id of radion button.
         * @param string $value - value to show.
         * @param array  $options - array of additional options.
         * @return nothing.
    */
    public function validate_mandatory() {
        //
        // check for empty form fields.
        //
        if(empty($this->submitted_data)) { return; }
        foreach($this->validation_mandatory as $val)   {
            if(isset($this->submitted_data[$val])) {
              if ( empty ($this->submitted_data[$val]))  {
                  $this->set_error('required_field' ,  array( 'field'=>$val) );
              }
            }
        }
        //
        // check for items in submitted_data NOT in $_POST/$_GET.
        //
        foreach($this->validation_mandatory as $val)   {
            if(!array_key_exists($val, $this->submitted_data   ))   {
                    $this->set_error('field_not_there' , array( 'field'=>$val) );
            }
        }
    }


        
    /**
         * validate_entries
         *
         * checks ALL entries in array validation_fields to ensure they are the
         * correct type, e.g. 'age'=>'numeric' etc... 
         * if not throw a error. 
         *
         * @param string $id    - id of radion button.
         * @param string $value - value to show.
         * @param array  $options - array of additional options.
         * @return nothing.
    */
    public function validate_entries() {
        //
        // check form data against expected values...number..email etc..
        //
        if(empty($this->submitted_data)) { return; }
        // loop round all validation fields...
        foreach( $this->validation_fields as $key=>$val ) {
                if(isset($this->submitted_data[$key])) {
                    $field_value = $this->submitted_data[$key];
                    // check value $field_value is of type $val.
                    $r=$this->check_value($val , $field_value);
                    if( $r  ==  true  ) {
                            $this->set_error('valid_entry' , array( 'field'=>$key , 'value'=>$val )  );
                    }        
                }   
        }
        // VALIDATE CAPTCHA..
        if($this->captcha==true) {
            $this->validate_captcha();
        }
        // VALIDATE SECURITY CODE
        if($this->security_code==true) {
            $this->validate_security_code();
        }
    }
    
    
        
    /**
         * check_value
         *
         * checks the field_value matches type.
         * e.g. 'age'=>'numeric' etc... 
         * if not throw a error. 
         * TYPES CURRENTLY TEST:    number.     string.     date.
         *                          email.      Ip Address. integer.
         *                          postcode.   zipcode.  
         * Expected Values:
         *                          number: '23', 23.34 etc.
         *                          string:  '' , 'hello' , '123ds'...
         *                          date:   2017-04-22.
         *                          email:  someone@somewhere.com
         *                          ip address: 10.0.0.1
         *                          integer: 10, 20, '12'
         *                          postcode: ss1 1pu
         *                          zipcode: 12345-1234
         * 
         * @param string $type          - type to check number, email etc....
         * @param string $field_value   - field value to check.
         * @return true/false -- true means there is a error.
    */    
    public function  check_value( $type , $field_value ) {
                if (!is_string($type))          { throw new Form_Builder_Exception("Form_Builder_Validation: {check_value} - $type  must be a string");    }            
                //
                // allow empty values to not be validated?
                //
                if($this->allow_empty==true ) {
                    if( empty($field_value)) {
                        $err=false;
                        return $err;
                    }
                }

                //
                // otherwise check for valid entry...
                //
                $err=false;
                $type = strtolower($type);
                switch( $type ) {
                    // NUMBER.
                    // True Examples: 34, 100.
                    case "number":
                        if (!is_numeric($field_value)) {
                                $err=true;
                        }
                        break;
                        
                    // STRING.
                    // True Examples: 'test', '1234', '' , '-'.
                    case "string":
                        if (!is_string($field_value)) {
                                $err=true;
                        }
                        break;
                        
                    // EMAIL
                    // True Examples: someone@somewhere.com
                    case "email":
                        if (!filter_var( $field_value , FILTER_VALIDATE_EMAIL)) {
                                $err=true;
                        }
                        break;
                       
                    // IP ADDRESS
                    // True Examples: 10.0.0.1
                    case "ip_address":
                        if (!filter_var( $field_value , FILTER_VALIDATE_IP)) {
                                $err=true;
                        }
                        break;
                        
                    // INTEGER>
                    // True Examples: '12' , 10 , 200.
                    case "integer":
                        if (!filter_var( $field_value , FILTER_VALIDATE_INT )== TRUE ) {
                                $err=true;
                        }
                        break;
                        
                    // DATE --  dd-mm-yyyy
                    // True Examples:  2017-03-22    
                    case "date":
                            $date = str_replace("/", "-", $field_value );
                            $d = explode("-"  , $date);
                            // 3 elements in array? day,month,year?
                            if (count($d)!=3) { $err=true; } else {
                                // 3 elements are all numbers?
                                if ( (is_numeric($d[0])) && (is_numeric($d[1])) && (is_numeric($d[2])) ) {
                                    if( checkdate( $d[1],$d[2],$d[0]) ) {
                                         $err=true;
                                    } else {$err=false;}
                                } else { $err=true; }
                            }
                        break;
                        
                    //TIME:
                    case "time":
                            if(preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/' , $field_value )) {
                                    $err=false;
                            } else { $err=true; }
                        break;
                        
                        
                    // POSTCODE.
                    // True Examples: SS1 1PU
                    case "postcode":
                            $postcoderegex = '/^([g][i][r][0][a][a])$|^((([a-pr-uwyz]{1}([0]|[1-9]\d?))|([a-pr-uwyz]{1}[a-hk-y]{1}([0]|[1-9]\d?))|([a-pr-uwyz]{1}[1-9][a-hjkps-uw]{1})|([a-pr-uwyz]{1}[a-hk-y]{1}[1-9][a-z]{1}))(\d[abd-hjlnp-uw-z]{2})?)$/i';
                            $pcheck = str_replace(' ','' , $field_value );
                            if (!preg_match( $postcoderegex , $pcheck)) {
                                $err=true;
                            }
                        break;
                        
                    // ZIP CODE.
                    // True Examples: 12345-1234
                    case "zipcode":
                            if (!preg_match('/^([0-9]{5})(-[0-9]{4})?$/i' , $field_value  ))  {
                                $err=true;
                            }
                        break;
                            
                }
                return $err;
    }
        
    

    /**
         * validate_captcha
         *
         * checks the captcha value entered matches the one displayed.
         * 
         * @param none.
         * @return true/false -- true means captcha entered is OK , false means it is wrong.
    */        
    public function validate_captcha() {
            if (session_id() ==  '' ) {session_start();}
            $result=true;
            if ( (isset($_SESSION['captcha_code'])) && (isset($this->submitted_data['captcha'])) ) {
                if ( $_SESSION['captcha_code']  != $this->submitted_data['captcha'] ) {
                    $this->set_error('captcha_invalid',array() );
                    $result=false;
                }
            } else {
                $result=false; 
                $this->set_error('captcha_invalid' , array() );
            }


            return $result;
    }



    /**
         * validate_security_code
         *
         * checks the security code matches the one originally generated.
         * 
         * @param none.
         * @return true/false -- true means code  is OK , false means it is wrong.
    */            
    public function validate_security_code() {
        $result=true;
        if($this->security_code==false) { return $result; }
        if (!isset($_SESSION['form_key']) || (!isset($this->submitted_data['form_key'])) ) {
            $result = false;
        } else {
            if ($_SESSION['form_key'] != $this->submitted_data['form_key']) {
                $result = false;
            }
        }
        if($result==false) $this->validation_errors[] = $this->set_error('form_invalid' , array() );
        
        return $result;
    }

    
    
        /**
         * validate_IsValid.
         *
         * tests a field is in range (<,>,etc) of $value.
         * E.g. value=34 , field='age' , operator='GREATER_THAN' --> Age>34 ?
         * 
         * @param string $value     - the value to compare.
         * @param string $field     - the field (posted value) to compare to $value.
         * @param string $operator  - the operator > , < , = , <> etc...
         * @return true/false       - true means the condition is true , false it is not.
        */
        public function validate_IsValid( $value='' , $field='' , $operator='='   )  {
                if (!is_string($field))     { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsValid} - $field  must be a string");    }            
                if (!is_string($operator))  { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsValid} - $operator  must be a string"); }
                $operator = strtoupper($operator);
                $operator_values = array( 'LESS_THAN' , 'GREATER_THAN' , 'NOT_EQUAL' , 'AFTER' , 'BEFORE' , 'EQUAL' , 'IN' , 'NOT_IN' );
                if (!in_array( $operator , $operator_values )) { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_isValid} - unknown operator"); }

                $condition_true=false;
                if ( !isset($this->submitted_data[$field])) { $condition_true = false; } else {
                    $field_value=$this->submitted_data[$field];     
                    switch($operator) {
                        case "LESS_THAN":       if($field_value <  $value) { $condition_true=true;} break;
                        case "GREATER_THAN":    if($field_value >  $value) { $condition_true=true;} break;
                        case "NOT_EQUAL":       if($field_value <> $value) { $condition_true=true;} break;
                        case "AFTER":           if($field_value >= $value) { $condition_true=true;} break;
                        case "BEFORE":          if($field_value <= $value) { $condition_true=true;} break;
                        case "EQUAL":           if($field_value == $value) { $condition_true=true;} break;    
                    }
                }
                // handle errors...
                if ( $condition_true == false ) {
                        $this->set_error('field_range' , array('field'=>$field , 'value'=>$value ));
                }

                return $condition_true;
        }
    
    
    
        /**
         * validate_IsBetween.
         *
         * tests a field is in between $start AND $end.
         * 
         * @param string $key               - The field name - e.g. date, age etc..
         * @param string $value             - the value to compare
         * @param string $start             - the start value.
         * @param string $end               - the end value.
         * @param boolean $include_values   - include the values in a comparison - e.g.
         * age>10 and age<20 if $include_values=false then it is age is 9 or below
         * if it is true then it is 10 or below and 20+
         * @return true/false       - true means the condition is true , false it is not.
        */
        public function validate_IsBetween( $key='' , $value='' , $start='' , $end='' , $include_values=true ) {
                if (!is_string($key))           { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsBetween} - $key    must be a string");    }
                if (!is_string($value))         { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsBetween} - $value  must be a string");    }
                if (!is_string($start))         { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsBetween} - $start  must be a string");    }
                if (!is_string($end))           { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsBetween} - $end    must be a string");    }
                if (!is_bool($include_values))  { throw new Form_Builder_Exception("Form_Builder_Validation: {validate_IsBetween} - $include_values   must be a boolean");    }
            
                $condition_true = true;
                if(isset($this->submitted_data[$value])) {
                    $v = $this->submitted_data[$value];
                    //$field_value = $this->submitted_data[$key];
//                    $e = $this->error_messages['field_between'];
  //                  $a = array( '[@field]' => $key ,'[@value1]' => $start,'[@value2]' => $end );
    //                $sub = $this->substitute( $a , $e  );

                    if($include_values==true){
                            if ( $v  <= $start || $value >= $end ) {
                                $this->set_error('field_between' , array('field'=>$key,'value1'=>$start,'value2'=>$end ));
                                $condition_true = false;
                            }
                    } else {
                            if ( $v  < $start || $value > $end )   {
                                $this->set_error('field_between' , array('field'=>$key,'value1'=>$start,'value2'=>$end ));
                                $condition_true = false;
                            }
                    }
                }
                return $condition_true;
        }
    
    

        /**
         * clean_data
         *
         * clean the submitted data to prevent things like xss scripting.
         * 
         * @param string $data              - The data to sanitise.
         * @param string $return_type       - type of data to return .. array..xml..json.
         * @return $data                    - the cleaned data.
        */        
        public function clean_data($data , $return_type='array' ) {
                if (!is_array($data))           { throw new Form_Builder_Exception("Form_Builder_Validation: {clean_data} - $data    must be a array");    }
                if (!is_string($return_type))   { throw new Form_Builder_Exception("Form_Builder_Validation: {clean_data} - $return_type    must be a string");    }

                foreach( $data  as $key=>$val  ) {
                    $cd     =   strip_tags($val);
                    $cd     =   htmlspecialchars($cd);
                    $data[$key]=$cd;
                }

                // return data as array, json or XML.
                switch ( $return_type ) {
                        case 'array':    $return_data = $this->validation_errors;               break;
                        case 'json':     $return_data = json_encode($this->validation_errors);  break;
                        case 'xml':      
                             $xml = new SimpleXMLElement('<rootTag/>');
                             $this->to_xml( $xml , $this->validation_errors );
                             $return_data =  $xml->asXML();
                             break;
                        default:         $return_data=$this->validation_errors;                 break;
                }

                
                return $return_data;
        }

        

        /**
         * to_xml.
         *
         * turn PHP array into XML object.
         * taken from : http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
         * 
         * @param SimpleXMLElement $object      - the xml object being created.
         * @param array $data                   - the data to turn in to XML.
         * @return completed XML.
        */                
        function to_xml(SimpleXMLElement $object, array $data)  {   
                if(!is_a($object , SimpleXMLElement ))      { throw new Form_Builder_Exception("Form_Builder_Validation: {to_xml} - $object    must be a SimpleXMLElement");    }
                if(!is_array($data))                        { throw new Form_Builder_Exception("Form_Builder_Validation: {to_xml} - $data    must be a array");    }

                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                            $new_object = $object->addChild($key);
                            to_xml( $new_object , $value );
                    } else {   
                            $object->addChild( $key , $value );
                    }
                }
        }
        
        
        
        
        // ######### END OF CLASS ###########
}




?>