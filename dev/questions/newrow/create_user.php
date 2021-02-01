<?php
/*
Bearer Request
https://stackoverflow.com/questions/30426047/correct-way-to-set-bearer-token-with-curl
include('connection.php');
  session_start();
  ob_start();
**/
//include "newrow.inc.php";
@session_start();
// if(isset($_SESSION['ses_admin_token'])){
//     echo 'ses_admin_token::';
//     echo $_SESSION['ses_admin_token']; die; 
// }

////////////////////
$launch_url = "https://smart.newrow.com/backend/lti/course" ;
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ;


$accesstoken="860d01f342cfbca27e7b0bd44946079c";

 if(!isset($_GET['email'])){
  exit('Enter Email id');
 }

 ///////////////

$get_user_email=$_GET['email'];

////////////////////////////////////
 //echo $_SESSION['ses_admin_token']; die; 
 $post = [
    'user_name' =>'test_tutor_101',
    'user_email' =>$get_user_email, //'test11@gmail.com',
    'first_name' =>'TestTutor',
     'last_name' =>'LastTestTutor',
     'CompanyUser' =>'Instructor', // Instructor | Student
    
    
    
    //'gender'   => 1,
];

//print_r($return_data); die; 
$token="ba2fcb22904057f9bf5ec0a2e785e3cd";

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