<?php 

/********
https://www.groupworld.net/room/2816/lunden
https://smart.newrow.com/room/?haf-136
@ Newrow functions
@newRowuser Management 
@No email notificatinon- Testing Session..
@ testing_session
*******/
$is_testing_on=0;//0|1

///////////////////////////
if($is_testing_on==1){
 $warnings_msg='Sorry, System in underdevelopment,';
echo $warnings_msg;
}
////////////////////////////
 $testing_tutor_id=125;
 $testing_tutor_email='imittal@techinventive.com';

 //////////////////////////////
 //newrow.curl.function
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

function _get_token(){

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

$oauth_access_token_secret='';
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
$newrowCurrToken=(!empty($newrowCurrToken))?$newrowCurrToken:false;
return $newrowCurrToken;

}
////////////////////////////////
 // echo 'NewrowToken::=='; echo  $getToken=_get_token(); die; 
 









/*
@Newrow new class

**/

///////////////////////////////////////////////
function _create_room($roomId){
  $var=$roomId; //$_GET['id'];

 $ses_room_id= (string)$var;
 $post = [
    'name' =>'Intervention_room'.$ses_room_id,
    'description' =>'Custom Intervention_room room by api.',
    //'avatar' =>'TestStudent',
     //'users' =>'Inst',
     'tp_id' =>$ses_room_id,
    
    
    
    
    //'gender'   => 1,
];

//print_r($return_data); die; 
$token=$_SESSION['ses_admin_token'];  //"cd3f2fcec5617f825e59a108405cd82e";

$ch = curl_init('https://smart.newrow.com/backend/api/rooms'); // Initialise cURL
       $post = json_encode($post); // Encode the data array into a JSON string
       $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
       $result = curl_exec($ch); // Execute the cURL statement
       $user_row= json_decode($result); 
       curl_close($ch); // Close the cURL connection
       ////////Display////////////
       return $user_row;

       // print_r($result);
       // exit();

     }





?>