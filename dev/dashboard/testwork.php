<?php

function HitPostCurl($apiUrl,$postFields)
{
	$handle = curl_init();
	curl_setopt_array($handle,
	array(
	CURLOPT_URL => $apiUrl,
	// Enable the post response.
	CURLOPT_POST       => true,
	// The data to transfer with the response.
	CURLOPT_POSTFIELDS => $postFields,
	CURLOPT_RETURNTRANSFER     => true,
	)
	);

	$data = curl_exec($handle);
	curl_close($handle);
	echo $data;

}

$apiUrl="https://drhomework.com/parent/updateTutorStatusApi.php";

$postFields=array('tutor_id'=>1081,'drhomework_ref_id'=>56);
HitPostCurl($apiUrl,$postFields);

?>