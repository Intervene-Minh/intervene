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

////////////////////
$launch_url = "https://smart.newrow.com/backend/lti/course" ;
$key = "z0rl-8b6m-lato-r7lh-2fvj" ;
$secret = "jul0-rxyb-h3px-8g1v-3bxj" ;


$accesstoken="c0ab66bcb19e984dccff0ec52ccd1e0e";


 //echo $_SESSION['ses_admin_token']; die; 
 // $ses_room_id=$_GET['room'];
// $var=$_GET['room']=2224;
 if(!isset($_GET['room'])){
   
  exit('Enter room id');
 }

 $var=$_GET['room'];


 $ses_room_id= (string)$var;
 $post = [
    'name' =>'Ses_room'.$ses_room_id,
    'description' =>'Custom room by api.',
    //'avatar' =>'TestStudent',
     //'users' =>'Inst',
     'tp_id' =>$ses_room_id,
    
    
    
    
    //'gender'   => 1,
];

//print_r($return_data); die; 
$token="c0ab66bcb19e984dccff0ec52ccd1e0e";

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



       print_r($result);
       exit();


      // return json_decode($result); // Return the received data

?>