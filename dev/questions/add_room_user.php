<?php
/*
Bearer Request
https://stackoverflow.com/questions/30426047/correct-way-to-set-bearer-token-with-curl
 $ses_room_id= (string)$var;

  //echo $_SESSION['ses_admin_token']; die; 
 // $ses_room_id=$_GET['room'];
// $var=$_GET['room']=2224;
**/

@session_start();
// print_r($_SESSION); die; 
////////////////////
$launch_url = "https://smart.newrow.com/backend/lti/course" ;
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ; 
$accesstoken="c0ab66bcb19e984dccff0ec52ccd1e0e";

 


 if(!isset($_GET['newrow_ref_room'])){

    exit('Enter newrow_ref_room of user');
    
 }

 if(!isset($_SESSION['ses_admin_token'])){
 echo 'Token not found! '; die; 
}
if(!isset($_GET['newrow_ref_room'])){
  exit('newrow_ref_room missing');
}


 ///////////Validation/////////////////////////////

 $var=$_GET['room'];$userArr=[];
 $userArr=array('32284','32287','32306');

 $post = [
    'enroll_users' =>array('32284','32287','32306'),
    //'unenroll_users' =>'Custom room by api.',
    
];


//$Testing_room_id=23995;//Ses_room2240.
$token=$_SESSION['ses_admin_token'];



$Testing_room_id=(isset($_GET['newrow_ref_room']))?$_GET['newrow_ref_room']:23995;

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