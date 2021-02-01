<?php 

/***
//$key = ​ "z0rl-8b6m-lato-r7lh-2fvj"​ ;
//$secret = ​ "jul0-rxyb-h3px-8g1v-3bxj"​ ;

****/

include "libraries/OAuthRequest.php";


$msg='Create User ....';
echo $msg; 

$key = ​ "z0rl-8b6m-lato-r7lh-2fvj"​ ;
$secret = ​ "jul0-rxyb-h3px-8g1v-3bxj"​ ;


$auth_login_url="https://smart.newrow.com/backend/api/auth/login"​ ;
//$key = ​   //"KEY_GIVEN_BY_NEWROW"​ ;
//$secret = ​ "SECRET_GIVEN_BY_NEWROW"​ ;
$user_id =""​; ​ // Newrow user id - optional
$post_data = [];
$post_data[​ "user_id"​ ] = $user_id;
$consumer = ​ new​ OAuthConsumer($key, $secret);
$request =OAuthRequest::from_consumer_and_token($consumer, ​ NULL​ , ​ "POST"​ , $auth_login_url,
$post_data);

$request->sign_request(​ new​ OAuthSignatureMethod_HMAC_SHA1(), $consumer, ​ NULL​ );
$url = sprintf(​ "%s?%s"​ , $auth_login_url, OAuthUtil::build_http_query($post_data));
$ch = curl_init();


die; 

$headers = ​ array​ ($request->to_header());

//////////////////////////////////////
curl_setopt($ch,
curl_setopt($ch,
curl_setopt($ch,
curl_setopt($ch,
CURLOPT_HTTPHEADER, $headers);
CURLOPT_URL, $url);
CURLOPT_RETURNTRANSFER, ​ TRUE​ );
CURLOPT_POSTFIELDS, $post_data);
$response = curl_exec($ch);
// parse response
$response = json_decode($response);
if​ ($response->status == ​ "success"​ ) {
// get token
$userToken = $response->data->token;
// save user token
// ...
} ​ else​ {
// Error occurred
echo​ $response->data->message;
}




?>