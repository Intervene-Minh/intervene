<?php

function getToken($serviceName)
{


 	$HitURl= "https://ellpractice.com/intervene/moodle/login/token.php";
	$postData = array(
		'username' => 'admin',
		'password' => 'Intervene123!',
		'service' =>  $serviceName,// this is service which from token you want
	);

	$ch = curl_init();
	$curlConfig = array(
    CURLOPT_URL            => $HitURl,
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => $postData
	);
	curl_setopt_array($ch, $curlConfig);
	$result = curl_exec($ch);
	curl_close($ch);
	$dataRe=json_decode($result);
	/* return token*/
	return $dataRe->token;
}


getToken();// call this function with service name.
?>