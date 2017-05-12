<htm>
<head>
<script src="http://code.jquery.com/jquery-latest.min.js"  type="text/javascript"></script>
<script>
function process_form() {
        var pname  = $('#name').val();
        var pemail = $('#email').val();
        var ppass  = $('#password').val();
        var pcap   = $('#captcha').val();
        $.ajax({
          url: 'form9_ajax.php',
          type: 'GET',
          data: 'pname='+pname+'&pemail='+pemail+'&ppass='+ppass+'&pcap='+pcap,
          success: function(data) {
                var obj = jQuery.parseJSON( data );
                    // are there errors??
                    if ((typeof(obj.errors)) !== "undefined" && obj.errors) {
                        $.each( obj.errors , function(key,value) {
                            alert(key + ':' + value );
                        });
                    } else {
                        // no errors so just output data....
                        alert("NO ERRORS!");
                        alert( "DATA: "+pname+' '+pemail+' '+ppass+' '+pcap);
                    }
                  },
          error: function(e) {
                //called when there is an error
                //console.log(e.message);
          }
        });
}
</script>
</head>
<body>
<?php
//
// basic contact form
//
echo '<h1>basic form 9 -- Registration form handled using AJAX.</h1>';
echo 'NOTE: At the top of this example we now handle data using AJAX.';
// include main class.
include_once('includes/Form_Builder.php');
$form = new Form_Builder();
try {
    $form->addForm  ( "form9" , "post" , "example_form9.php"  );
    $form->addGeneralField( "<fieldset>" );
    $form->addLabel ( "Enter name" );
    $form->addInput ( "name" ,  "" , "text" );
    $form->addGeneralField('<br />');
    $form->addLabel ( "Email Address" );
    $form->addInput( "email");
    $form->addGeneralField('<br />');
    $form->addLabel ( "Password" );
    $form->addInput ( "password", "" ,"password" );
    $form->addGeneralField('<br />');
    $form->addCaptcha();
    $form->addGeneralField('<br />');
    $form->addSubmit( "btnSubmit" , "OK", array('onclick'=>"JavaScript:process_form(); return false;") );
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
