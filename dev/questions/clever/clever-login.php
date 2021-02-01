<?php
	/* INCLUDE CLEVER API FUNCTION FILE */
	require_once $_SERVER['DOCUMENT_ROOT'].'/questions/clever/clever.keys.php';
	
	/* set redirect url*/
	$redirectURl ="https://clever.com/oauth/authorize?response_type=code&redirect_uri=$redirect_uri&client_id=$client_id&district_id=$district_id";

	echo "<script> window.location='".$redirectURl."'</script>";
?>