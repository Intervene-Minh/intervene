<?php
/*
Bearer Request
https://stackoverflow.com/questions/30426047/correct-way-to-set-bearer-token-with-curl

**/
//include "newrow.inc.php";
@session_start();
// if(isset($_SESSION['ses_admin_token'])){
//     echo 'ses_admin_token::';
//     echo $_SESSION['ses_admin_token']; die; 
// }

// print_r($_SESSION); die; 
////////////////////
$launch_url = "https://smart.newrow.com/backend/lti/course" ;
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ;


$accesstoken="c0ab66bcb19e984dccff0ec52ccd1e0e";


 //echo $_SESSION['ses_admin_token']; die; 
 // $ses_room_id=$_GET['room'];
// $var=$_GET['room']=2224;
 if(!isset($_GET['newrow_ref_id'])){

    exit('Enter newrow_ref_id of user');
    
 }

 $var=$_GET['room'];
 $userArr=[];
 $userArr[]='32069';// 32070

 // Tutor id :  32306


 $ses_room_id= (string)$var;
 $post = [
    'enroll_users' =>array('32284','32287','32306'),
    //'unenroll_users' =>'Custom room by api.',
    //'avatar' =>'TestStudent',
     //'users' =>'Inst',
     //'tp_id' =>$ses_room_id,
    
    
    
    
    //'gender'   => 1,
];

//print_r($return_data); die; 
// $token="8583d607dbc375b98d553d679b90ba6a";

if(!isset($_SESSION['ses_admin_token'])){
 echo 'Token not found! '; die; 
}

$token=$_SESSION['ses_admin_token'];

if(!isset($_GET['newrow_ref_id'])){
  exit('newrow_ref_id missing');
}



//$Testing_room_id=23995;//Ses_room2240.

$Testing_room_id=(isset($_GET['newrow_ref_id']))?$_GET['newrow_ref_id']:23995;

$RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$Testing_room_id;


$api_url='rooms/participants/<room_id>​';

   $ch = curl_init($RoomUrlLink); // Initialise cURL


       $post = json_encode($post); // Encode the data array into a JSON string
       $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields


       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
       $result = curl_exec($ch); // Execute the cURL statement
       $user_row= json_decode($result); 
       curl_close($ch); // Close the cURL connection


         if(!$result) {
          echo 'No response!';die;
            //return false;
          }



       print_r($result);
       exit();


      // return json_decode($result); // Return the received data

?>