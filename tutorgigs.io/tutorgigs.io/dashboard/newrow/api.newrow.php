<?php

/***
Lti Key :: z0rl-8b6m-lato-r7lh-2fvj
===============
Lti Secret : jul0-rxyb-h3px-8g1v-3bxj
#

**/


// ------------------------------
// CONFIGURATION SECTION
// ------------------------------
$launch_url =  "https://smart.newrow.com/backend/lti/course" ;
$key =  "z0rl-8b6m-lato-r7lh-2fvj" ;  //LTI_KEY_GIVEN_BY_NEWROW
$secret =  "jul0-rxyb-h3px-8g1v-3bxj" ; # LTI_SECRET_GIVEN_BY_NEWROW
$launch_data =  array (
// Consumer identifier (Vendor name like: desire2learn, moodle, etc.)
"tool_consumer_info_product_family_code" =>  "newrowTest" ,
//------------------
// User details
//------------------
// Internal authenticated user id in your system
"user_id" =>  "2928321264" ,
// User role ('Administrator','Instructor', 'Student')
"roles" =>  "Student" ,
// User email
"lis_person_contact_email_primary" =>  "sam@school.edu" ,
// User first name
"lis_person_name_given" =>  "Sam" ,
// User last name
"lis_person_name_family" =>  "Thompson" ,
//------------------
// Room details
//------------------
// Internal Room identifier
"context_id" =>  "4512313" ,
// Room name
"context_title" =>  "Design of Personal Environments" ,);
// ------------------------------
// OAUTH CONFIGURATION
// ------------------------------
$now =  new DateTime();
$launch_data[ "lti_version" ] =  "LTI-1p0" ;
# Basic LTI uses OAuth to sign requests
# OAuth Core 1.0 spec: http://oauth.net/core/1.0/
$launch_data[ "oauth_callback" ] =  "https://tutorgigs.io/login.php" ; //https://tutorgigs.io/login.php
$launch_data[ "oauth_consumer_key" ] = $key;
$launch_data[ "oauth_version" ] =  "1.0" ;
$launch_data[ "oauth_nonce" ] = uniqid( '' ,  true );
$launch_data[ "oauth_timestamp" ] = $now->getTimestamp();
$launch_data[ "oauth_signature_method" ] =  "HMAC-SHA1" ;
# In OAuth, request parameters must be sorted by name
$launch_data_keys = array_keys($launch_data);
   //print_r($launch_data);

sort($launch_data_keys);
$launch_params =  array ();
foreach ($launch_data_keys  as $key) {
array_push($launch_params, $key .  "=" . rawurlencode($launch_data[$key]));
}
$base_string =  "POST&" . urlencode($launch_url) .  "&" . rawurlencode(implode( "&" ,
$launch_params));
$secret = urlencode($secret) .  "&" ;
$signature = base64_encode(hash_hmac( "sha1" , $base_string, $secret,  true ));

////////////////////////
  //echo 'Data of API =='.$launch_url;




?>

<form id="ltiLaunchForm"  name="ltiLaunchForm" method="POST" action="<?=$launch_url?>">
  <?php  
   foreach ($launch_data as $key => $value) {
  
  ?>
  <?=$key?>:<br>
  <input type="text" name="<?=$key?>" value="<?=$value?>">
  <br>

  <?php }  ?>
 


  <br>

   <input type="text" name="oauth_signature" value="<?=$signature?>">

  <input style="background: red;" type="submit" name="Send" value="submit"   >
</form> 

<!-- <script type="text/javascript">
   document.getElementById("ltiLaunchForm").style.display="none";
  document.ltiLaunchForm.submit();

</script>
 -->

