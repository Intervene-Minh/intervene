<?php
/**
@User related URL 


**/

@session_start();
////////////////////////////////////
$userId=time();
$get_user_email='rohit'.$userId.'@gmail.com';

$student_arr=array('32284','32287');

$user_newrow_id='32287';
 //echo $_SESSION['ses_admin_token']; die; 
 $post = [
    'user_id' =>$user_newrow_id,
   
    
    
    //'gender'   => 1,
];

$n_room_id='24041';
  $n_student_id='32284';
///////////////////////
$token=$_SESSION['ses_admin_token']; // 5fc8c417a486296fb3fc334293b2b54c

//$token = "YOUR_BEARER_AUTH_TOKEN";

 $url='https://smart.newrow.com/backend/api/rooms/url/23995';
//setup the request, you can also use CURLOPT_URL
// $ch = curl_init('https://smart.newrow.com/backend/api/rooms/url/23995');

 $ch = curl_init('https://smart.newrow.com/backend/api/rooms/url/24041?user_id=32284');

// Returns the data/output as a string instead of raw data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Authorization: Bearer ' . $token
   ));

// get stringified data/output. See CURLOPT_RETURNTRANSFER
$data = curl_exec($ch);
print_r($data); die; 

// get info about the request
$info = curl_getinfo($ch);
// close curl resource to free up system resources
curl_close($ch);


?>

<iframe src=""  allow="microphone; camera"  height="100%" width="100%">
</iframe>