<?php
# Form_Builder_Exception class 
# coded by Ian Sherman ITECH
# e-mail : info@itech123.co.uk 
# year: 2017

class Form_Builder_Exception extends Exception{
  public function errorMessage() {
    //error message
    $errorMsg = 'Error on line '.$this->Line().' in '.$this->File()
    .': <b>'.$this->Message().'</b> is not a valid E-Mail address';
    return $errorMsg;
  }
}