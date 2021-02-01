<?php
 error_reporting(-1);
 ini_set('display_errors', 1);

/////////////////////
$url = "https://smart.newrow.com/backend/api/users/info";


$token = "c14302dc…………….dab5a132";


$post_data = [];
$ch = curl_init();
$authorization = "Authorization: Bearer " . $token;

 


//////////////////////////////////////


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( $authorization ));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
$response = curl_exec($ch);

 print_r($response); die; 

 

curl_close($ch);
// do anything you want with your response
$response = json_decode($response);
if ($response->status == "success") {
 // get user info
 $userInfo = $response->data;
} else {
 // Error occurred
 $errorMessage = $response->data->message;
}

?>