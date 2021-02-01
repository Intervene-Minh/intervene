<?php

/*
	KEVIN PRYCE
	UPLOAD TEST FILE
	Testing for site integrity while multiple users upload. 
	This file purposely doesn't require session user so we can test server capabilites uninterrupted
*/


/* connection file*/
include('../inc/connection.php'); 
/* extract request data*/
extract($_REQUEST);
/* session start*/
session_start();
ob_start();

if ( isset($_FILES['file']) and !$_FILES['file']['error']) {
    $fi = new FilesystemIterator(__DIR__, FilesystemIterator::SKIP_DOTS);

	$file_name = iterator_count($fi).'-k-'.$_FILES['file']['name'];
	echo $file_name . ' Received Successfully. ';	

	if ( move_uploaded_file($_FILES['file']['tmp_name'], "./".$file_name) ) {
		echo $file_name . ' Moved Successfully';
	}
}

?>
