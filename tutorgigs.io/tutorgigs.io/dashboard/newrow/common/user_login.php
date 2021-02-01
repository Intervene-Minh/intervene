<?php
   error_reporting(-1);
ini_set('display_errors', 1);
  include "common/OAuthConsumer.php";
//////////////////////////
$launch_url =  "https://smart.newrow.com/backend/lti/course" ;
$key =  "z0rl-8b6m-lato-r7lh-2fvj" ;  //LTI_KEY_GIVEN_BY_NEWROW
$secret =  "jul0-rxyb-h3px-8g1v-3bxj" ; # LTI_SECRET_GIVEN_BY_NEWROW

//////////////////////////////
$auth_login_url = "https://smart.newrow.com/backend/api/auth/login";

// $key = "KEY_GIVEN_BY_NEWROW";
// $secret = "SECRET_GIVEN_BY_NEWROW";


$user_id = "64466"; // Newrow user id - optional
$post_data = [];
$post_data["user_id"] = $user_id;

  //print_r($post_data); die;


$consumer = new OAuthConsumer($key, $secret);

// print_r($consumer); die; 


$request = OAuthRequest::from_consumer_and_token($consumer, NULL,"POST", $auth_login_url,
$post_data);
$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);


$url = sprintf("%s?%s", $auth_login_url, OAuthUtil::build_http_query($post_data));
$ch = curl_init();

///////////////
$headers= array($request->to_header());

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$response = curl_exec($ch);
// parse response
$response = json_decode($response);


if ($response->status == "success") {
 // get token
 $userToken = $response->data->token;
 // save user token
 // ...
} else {
 // Error occurred
 echo $response->data->message;
}





?>