<?php
session_start();
ob_start();
$obs_arr=array(); 
$_SESSION['observer_id']=$observerId=01335;
$_SESSION['observer_name']='Admin_1';


$obs_arr['lis_person_contact_email_primary']="observer_{$observerId}@intervene.com";// student_id

$obs_arr['lis_person_name_given']=(isset($_SESSION['observer_name']))?$_SESSION['observer_name'].$_SESSION['observer_id']:'TestObserver';


 $curr_ses_id=79999;


 $obs_arr['live_ses_id']=(isset($curr_ses_id))?$curr_ses_id:100099;

// CONFIGURATION SECTION
// ------------------------------
$launch_url = "https://smart.newrow.com/backend/lti/course" ;
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ;
$launch_data = array (
// Consumer identifier (Vendor name like: desire2learn, moodle, etc.)
"tool_consumer_info_product_family_code" => "newrowTest" ,
//------------------
// User details
//------------------
// Internal authenticated user id in your system
"user_id" =>$observerId, //"1234" ,
// User role ('Administrator','Instructor', 'Student')
"roles" => "student" ,
// User email
"lis_person_contact_email_primary" =>$obs_arr['lis_person_contact_email_primary'], //"sam@school.edu" ,
// User first name
"lis_person_name_given" => $obs_arr['lis_person_name_given'] ,
// User last name
"lis_person_name_family" => "observer" ,
//------------------
// Room details
//------------------
// Internal Room identifier
"context_id" =>$curr_ses_id,// "4512313"
// Room name
"context_title" => "Intervention_room".$curr_ses_id,// Tutorgigs
);
/////////////////////
// echo  'launch_data===';
// print_r($launch_data);

// die ;





// ------------------------------
// OAUTH CONFIGURATION
// ------------------------------
$now = new DateTime();
$launch_data[ "lti_version" ] = "LTI-1p0" ;
# Basic LTI uses OAuth to sign requests
# OAuth Core 1.0 spec: http://oauth.net/core/1.0/
$launch_data[ "oauth_callback" ] = "about:blank" ;
$launch_data[ "oauth_consumer_key" ] = $key;
$launch_data[ "oauth_version" ] = "1.0" ;
$launch_data[ "oauth_nonce" ] = uniqid( '' , true );
$launch_data[ "oauth_timestamp" ] = $now->getTimestamp();
$launch_data[ "oauth_signature_method" ] = "HMAC-SHA1" ;
# In OAuth, request parameters must be sorted by name
$launch_data_keys = array_keys($launch_data);
sort($launch_data_keys);
$launch_params = array ();
foreach ($launch_data_keys as $key) {
array_push($launch_params, $key . "=" . rawurlencode($launch_data[$key]));
}
$base_string = "POST&" . urlencode($launch_url) . "&" . rawurlencode(implode( "&" ,
$launch_params));
$secret = urlencode($secret) . "&" ;
$signature = base64_encode(hash_hmac( "sha1" , $base_string, $secret, true ));
?>
<form id= "ltiLaunchForm" name= "ltiLaunchForm" method= "POST" action= "<?php
printf($launch_url); ?>" >
<?php foreach ($launch_data as $k => $v ) { ?>
<input type= "hidden" name= "<?php echo $k ?>" value= "<?php echo $v ?>" >
<?php } ?>
<input type= "hidden" name= "oauth_signature" value= "<?php echo $signature ?>" >
</form>
<script language= "javascript" >
document.getElementById( "ltiLaunchForm" ).style.display = "none" ;
document.ltiLaunchForm.submit();
</script>