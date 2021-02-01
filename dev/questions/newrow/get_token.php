<?php 
/**
https://wordpress.stackexchange.com/questions/213006/how-to-use-oauth-authentication-with-rest-api-via-curl-commands

**/

//phpinfo(); die; 
// error_reporting(-1);
// ini_set('display_errors', 1);
@session_start();
// if(isset($_SESSION['ses_admin_token'])){
//     echo 'token::';
//     echo $_SESSION['ses_admin_token']; die; 
// }

////////////////////
$launch_url = "https://smart.newrow.com/backend/lti/course" ;
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ;


$accesstoken="860d01f342cfbca27e7b0bd44946079c";
##############################
 function buildBaseString($baseURI, $method, $params){
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value);
    }

    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth){
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";

    $r .= implode(', ', $values);
    return $r;
}

// Add request, authorize, etc to end of URL based on what call you're making
$url = "https://smart.newrow.com/backend/api/auth/login";
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ;

$consumer_key ="z0rl-8b6m-lato-r7lh-2fvj" ;   //"CLIENT ID HERE";
$consumer_secret ="jul0-rxyb-h3px-8g1v-3bxj" ;  //"CLIENT SECRET HERE";

$oauth = array( 'oauth_consumer_key' => $consumer_key,
                'oauth_nonce' => time(),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_callback' => 'oob',
                'oauth_timestamp' => time(),
                'oauth_version' => '1.0');

// $base_info = buildBaseString($url, 'GET', $oauth);
// $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);

// $oauth_access_token_secret='';
$base_info = buildBaseString($url, 'GET', $oauth);
$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);


$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;


$header = array(buildAuthorizationHeader($oauth), 'Expect:');
$options = array( CURLOPT_HTTPHEADER => $header,
                  CURLOPT_HEADER => false,
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_SSL_VERIFYPEER => false);

$feed = curl_init();
curl_setopt_array($feed, $options);
$json = curl_exec($feed);
curl_close($feed);

$return_data = json_decode($json);
//echo $json; die;
$newrowCurrToken=$return_data->data->token;
$_SESSION['ses_admin_token']=$newrowCurrToken;
//echo 'Res===';
print_r($return_data); die; 


?>