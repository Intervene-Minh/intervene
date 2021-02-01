<?php
/***
@Backup file 
@Create tutor at-newrow
**/
@session_start();
#include("header.php");
include('libraries/newrow.functions.php');


 $getToken=_get_token(); 

////////////////////////////////////
$userId=time();
$get_user_email='Test'.$userId.'@gmail.com';

//echo 'TT==='.$getToken;




 $post = [
    'user_name' =>'Demotest_tutor_'.$userId,
    'user_email' =>$get_user_email, //'test11@gmail.com',
    'first_name' =>'Rohit',
     'last_name' =>'Tutor',
     'role' =>'Instructor', // Instructor | Student {CompanyUser}
    
    
    
    //'gender'   => 1,
];



###$token=$_SESSION['ses_admin_token']; // 5fc8c417a486296fb3fc334293b2b54c
$token=$getToken;




if(empty($token)){
  exit('Admin token not found! ');
}

///////////////////////

$ch = curl_init('https://smart.newrow.com/backend/api/users'); // Initialise cURL
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



       print_r($user_row);exit();  //die;

      // return json_decode($result); // Return the received data

?>