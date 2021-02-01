
<?php
/**
@ File code at-Ifrmed

**/
//echo 'tutorgigs newrow board  <br/>';
 session_start();
 ob_start();
 //print_r($_SESSION); die;

 $ses_arr=array();
$ses_arr['tutor_em']=(isset($_SESSION['login_mail']))?$_SESSION['login_mail']:'rdiwedi@techinventive.com';
$ses_arr['lis_person_name_given']= $_SESSION['login_user'] ;
// "lis_person_name_family" => "verma" ,
$ses_arr['lis_person_name_family']=''; 

$ses_arr['live_ses_id']=(isset($_SESSION['live_ses_id']))?$_SESSION['live_ses_id']:100099; 
$ses_arr['tutor_id']=(isset($_SESSION['ses_teacher_id']))?$_SESSION['ses_teacher_id']:99;

$context_title='NewContent10001'; //  Virtual class

$ContextId= "4512313";


//// 
// ------------------------------
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
"user_id" =>"101", //"101" ,
// User role ('Administrator','Instructor', 'Student')
"roles" => "Instructor" ,
// User email
"lis_person_contact_email_primary" => $ses_arr['tutor_em'] ,
// User first name
"lis_person_name_given" =>$ses_arr['lis_person_name_given'] ,
// User last name
"lis_person_name_family" => "Tutor" ,
//------------------
// Room details
//------------------
// Internal Room identifier
"context_id" => "4512313" ,
// Room name
"context_title" => "Session_".$ses_arr['live_ses_id'] ,// Tutorgigs
);
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
<form id= "ltiLaunchForm"  target="_self" name= "ltiLaunchForm" method= "POST" action= "<?php
printf($launch_url); ?>" >
<?php foreach ($launch_data as $k => $v ) { ?>
<input type= "text" name= "<?php echo $k ?>" value= "<?php echo $v ?>" >
<?php } ?>
<input type= "text" name= "oauth_signature" value= "<?php echo $signature ?>" >
</form>

<script language= "javascript" >
// document.getElementById( "ltiLaunchForm" ).style.display = "none" ;
// document.ltiLaunchForm.submit();
</script>