<script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../js/tinymce/plugins/asciimath/js/ASCIIMathMLwFallback.js"></script>
<script type="text/javascript">
var AMTcgiloc = "http://www.imathas.com/cgi-bin/mimetex.cgi";  		//change me
document.write(AMTparseMath(<?php echo $_GET['str']; ?>));
</script>