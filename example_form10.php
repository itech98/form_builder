<htm>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="http://code.jquery.com/jquery-latest.min.js"  type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                        $("#dialog").hide();
                        // output the data....
                        var r = '<b>NAME:</b>'+pname+'<br /><b>EMAIL:</b>'+pemail+'<br /><b>PASS:</b>'+ppass+'<br /><b>CAPTCHA:</b>'+pcap;
                        $("#result").html(r);
                    }
          },
          error: function(e) {
                //called when there is an error
                //console.log(e.message);
          }
        });
}



$().ready(function ($) {    
    // initially hide the dialog box.
    $("#dialog").hide();
 
 
    // user clicks reg button...perform AJAX call to get the form.
    $("#reg").click(function() {
        $.ajax({
          url: 'form10_ajax.php',
          type: 'GET',
          data: 'show=results',
          success: function(data) {
                 $("#form1").html(data);
                 $( "#dialog" ).dialog();
            },
          error: function(e) {
                //called when there is an error
                //console.log(e.message);
          }
        });
    });
    
    
    
});
</script>
</head>
<body>
<?php
//
//
echo '<h2>Welcome To Blogs Autos</h2>';
echo 'We buy and sell anything as long as its under 500 quid!!';
echo 'Click here to register...';
echo '<button value="register" id="reg" name="reg">REGISTER</button>';
 
echo '<div id="dialog" title="Basic dialog">';
echo "  <p id='form1'></p>";
echo '</div>';
echo '<div id="result"></div>';
?>
