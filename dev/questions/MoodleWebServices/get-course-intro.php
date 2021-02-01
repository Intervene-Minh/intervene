<?php
	$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
	require_once($Directorypath.'cur-moodlel.php');
extract($_REQUEST);
$domainname = $webServiceURl;
// set post fields
$post = ['instanceID' =>$instanceID];

$ch = curl_init($domainname.'/get_course-description.php');

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// execute!
$response = curl_exec($ch);

//print_r($response);
//if ($response === false) 
    //$response = curl_error($ch);

//echo stripslashes($response);
// close the connection, release resources used
curl_close($ch);

/* get  data in json formta*/
$responseData     =     json_decode($response);

$questionName     =     $responseData->name;
$content          =     $responseData->intro;

$introOfQuestion  =     $content;
?>

