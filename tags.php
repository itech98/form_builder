<?php
# tags class 
# coded by Ian Sherman ITECH
# e-mail : info@itech123.co.uk 
# year: 2017
final class tags    {
	private $tag_type;		// tag type -- e.g. input, select, form etc.
	// GENERAL FIELD ATTRIBUTES.
	private $id;		// tag id.
	private $type;		// input type -- e.g. text/password/hidden etc.
	private $value;		// value to show.
	private $options;       // array of extra attributes.
        private $tag_array;     // array of primary options -- e.g. input type, id etc.
	// TEXTAREA.
	private $rows;			// textarea rows.
	private $cols;			// textarea cols.
	// FORM.
	private $method;		// form method post/get.
	private $action;		// action after post.
	// LABEL.
	private $for;			// label for.
	// IMAGE.
	private $width;			// image width.
	private $height;		// image height.
	private $src;			// image source.
        // FILE UPLOADS
        private $upload_dir='';         // upload dir.
        private $create_if_not_exist;   // create dir if not exists.

        
        
	public function   __construct( $ar=array() , $options=array()  ) {
//            try {
                $this->array_elements[] = $ar;
                $this->tag_array = $ar;
                foreach($ar as $key=>$val ) {
                            $this->{$key}=$val;
                }
                $this->options=$options;

                //
                // HANDLE EXCEPTIONS.
                //
                if ( $this->id != '' )     { if (!is_string($this->id))     { throw new Form_Builder_Exception("tags: {constructor} - image ID must be a string"); } }
                if ( $this->type != '' )   { if (!is_string($this->type))   { throw new Form_Builder_Exception("tags: {constructor} - type must be a string"); } }
                if ( $this->value != '' )  { if ((!is_string($this->value)) && (!is_numeric($this->value)))  { throw new Form_Builder_Exception("tags: {constructor} - value must be a string"); } }
                if(!is_array($this->options))   { throw new Form_Builder_Exception("tags: {constructor} options must be a array."); }
                if(!is_array($this->tag_array)) { throw new Form_Builder_Exception("tags: {constructor} tag_array must be a array."); }                
        }
        
        
        
        private function tag_attributes() {
            $str =  '';
            
            // check it is a array?
            // any options to process? if not return empty string...
            if(empty($this->options)) { return $str; }

            // process options....
            foreach( $this->options as $key=>$val ) {
                $str .= " $key=\"$val\"";
            }

            return $str;
        }

        
        
	public function output() {
                  $tag='';
                  
                  // get any extra attributes...
                  $o = $this->tag_attributes();


                  $this->tag_type=  strtolower($this->tag_type);
                  switch($this->tag_type) {
                        case 'form':
                                $tag.="<form action=\"$this->action\" method=\"$this->method\"  $o >";
                                break;
			case 'label':
				$tag.="<label for=\"$this->for\" $o >$this->value</label>";
				break;
			case 'input':
				$tag="<input type=\"$this->type\" id=\"$this->id\" name=\"$this->id\" value=\"$this->value\"  $o  />";
				break;
			case 'image':
				$tag="<img src=\"$this->src\" id=\"$this->id\" name=\"$this->id\" height=\"$this->height\" width=\"$this->width\"  $o  />";
				break;
                        case 'button':
                                $tag ="<button id=\"$this->id\" name=\"$this->id\"  value=\"$this->value\"   $o >$this->value</button>";
                                break;
			case 'select':
				$tag="<select id=\"$this->id\"  name=\"$this->id\"   $o >";
                                if(isset($this->tag_array['select_options'])) {
                                    foreach( $this->tag_array['select_options'] as $opt ) {
					$tag .= "<option value=\"$opt\">$opt</option>";
                                    }
                                }
				$tag.='</select>';
				break;
			case 'radio':
				$tag="<input type=\"radio\" name=\"$this->id\" id=\"$this->id\" value=\"$this->value\"  $o >";
				break;
			case 'submit':
				$tag="<input type=\"submit\" name=\"$this->id\" id=\"$this->id\" value=\"$this->value\"  $o >";
				break;
			case 'checkbox':
				$tag="<input type=\"checkbox\" name=\"$this->id\" id=\"$this->id\" value=\"$this->value\"  $o >".$this->value;
				break;
                        case 'textarea':
                                $tag ="<textarea id=\"$this->id\" name=\"$this->id\" $o >$this->value</textarea>";
                                break;
			case 'endform':
				$tag="</form>";
				break;
                        case 'file':
                                $tag="<input type=\"file\" name=\"$this->id\" id=\"$this->id\" $o />";
                                break;
                        default:    // UNKNOWN TAG.
                                $tag= $this->tag_type;
                                break;
                }


                return $tag;
	}

        
        
	public function  __toString() {
            $this->output();
	}

}
?>