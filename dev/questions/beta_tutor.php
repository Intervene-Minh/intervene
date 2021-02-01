<?php
/**
@User related URL 
@Student Newrow board and login 
#Student_13500  32284,   
# Student_13501 , 32287
@Get tutor link
[imittal@techinventive.com  imittal@techinventive.com]
[32306]  =[125]

**/

@session_start();
  //print_r($_SESSION); die; 
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



echo 'Tutor Board====<br/>';


///////////////////////
$token=$_SESSION['ses_admin_token']; // 5fc8c417a486296fb3fc334293b2b54c
if(!isset($token)||empty($token)||!isset($_SESSION['ses_admin_token'])){
	exit('Token error');

}



//$token = "YOUR_BEARER_AUTH_TOKEN";

 $url='https://smart.newrow.com/backend/api/rooms/url/23995';
//setup the request, you can also use CURLOPT_URL
// $ch = curl_init('https://smart.newrow.com/backend/api/rooms/url/23995');// 32287, 
$test_newrow_id='32306';
$get_newrow_room_id='24041';



//////////////////
 $ch = curl_init('https://smart.newrow.com/backend/api/rooms/url/24041?user_id='.$test_newrow_id);

// Returns the data/output as a string instead of raw data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Authorization: Bearer ' . $token
   ));

// get stringified data/output. See CURLOPT_RETURNTRANSFER
$data = curl_exec($ch);
 $Board=json_decode($data);
 
  print_r($Board->data->url); die;
  if($Board->status=='success'){
    $_SESSION['ses_student_launch_url']=$Board->data->url;
  }
 
 //print_r($data); die; 

// get info about the request
$info = curl_getinfo($ch);
// close curl resource to free up system resources
curl_close($ch);
////////////////////////////////////////

// unset($_SESSION['ses_student_launch_url']) ;

if(isset($_SESSION['ses_student_launch_url'])&&!empty($_SESSION['ses_student_launch_url'])){

 echo  $_SESSION['ses_student_launch_url'];  //die; 




?>
<!-- 
<iframe src="<?php //print($_SESSION['ses_student_launch_url'])?>"  allow="microphone; camera"  height="100%" width="100%">
</iframe> -->
<?php } ?> 