<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>jQuery UI Signature Basics</title>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<link href="signature/css/jquery.signature.css" rel="stylesheet">
<style>
.kbw-signature { width: 400px; height: 200px; }
</style>
<!--[if IE]>
<script src="excanvas.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="signature/js/jquery.signature.js"></script>
<script>
$(function() {
    var sig = $('#sig').signature();
    $('#disable').click(function() {
        var disable = $(this).text() === 'Disable';
        $(this).text(disable ? 'Enable' : 'Disable');
        sig.signature(disable ? 'disable' : 'enable');
    });
    $('#clear').click(function() {
        sig.signature('clear');
    });
    // JSON

    // $('#json').click(function() {
    //     alert(sig.signature('toJSON'));
    // });

    $('#svg').click(function() {
          var info='<p><strong>Signature</strong>(Result to save)</p>';
        var sign_res=sig.signature('toSVG');
        // sign_done, is_sign_done
        $('#is_sign_done').val(1);
        $('#sign_done').val(sign_res);

        $('#sign_result').html(info+''+sign_res);
        //Set CSS
        //$("svg").css({"width": "200px", "height": "169px"});
        $("svg").css({"width": "100%", "height": "169px","border-bottom": "1px solid"});

       // alert(sig.signature('toSVG'));
    });
});
</script>
</head>
<body>
<h2>Signature below and click to "Confirm, to Save" then - Go to <button type="button" name="RequestStop" class="btn btn-primary btn-sm" id="btn btn-lg btn-primary">Submit Form</button> </h2>
<br/>

<!-- <h1>jQuery UI SignatureXXXXXXXXXXXXx</h1>
<p>This page demonstrates the very basics of the
    <a href="http://keith-wood.name/signature.html">jQuery UI Signature plugin</a>.
    It contains the minimum requirements for using the plugin and
    can be used as the basis for your own experimentation.</p>
<p>For more detail see the <a href="http://keith-wood.name/signatureRef.html">documentation reference</a> page.</p>
<p>Default signature:</p> -->

<div class="form-row">
 <div id="contentStop" class="col-md-6">

 <div id="sig" ></div>
<p style="clear: both;">
    <button id="disable">Disable</button> 
    <button  id="clear">Clear</button> 
    
    <!-- <button id="svg">To SVG</button> -->
    <button type="button" id="svg">Confirm, to Save</button>
</p>

 </div>
<!--   Output rows -->
<div id="contentStop" class="col-md-6">
 <div id="sign_result"></div>
 </div>


 </div>
                                               











<!-- <dl>
    <dt>Github</dt><dd><a href="https://github.com/kbwood/signature">https://github.com/kbwood/signature</a></dd>
    <dt>Bower</dt><dd>kbw-signature</dd>
</dl> -->


</body>
</html>
