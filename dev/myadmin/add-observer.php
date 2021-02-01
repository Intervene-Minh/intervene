<?php

include('newrow.functions.php');

session_start();
extract($_REQUEST);

$token=$_SESSION['getToken']=_get_token(); 
$newrow_id= 38892;


$newrow_room_id= $roomID;//25953;


//step 1 create modrator
if($newrow_id ==0 )
{
 
    /* modareate details*/  
    $Tutor['email']="coach@tutogigs.io";
    $Tutor['f_name']="Instructional";
    $Tutor['l_name']="Coach";
    $userId=time();
    $get_user_email = (string)$Tutor['email'];

    $post = [
    'user_name' =>'Tutor_'.$userId,
    'user_email' =>$get_user_email, //'test11@gmail.com',
    'first_name' =>$Tutor['f_name'], //'Mastertutor',
    'last_name' =>$Tutor['l_name'],
    'role' =>'moderator',// Instructor | Student {CompanyUser}
    ];


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
    curl_close($ch); 
    
    $newrow_user_id=$user_row->data->user_id;

   // print_r($user_row);

    if($newrow_user_id > 0 ){

      $_SESSION['newrow_user_id']=$newrow_user_id;

 $newrow_id =$_SESSION['newrow_user_id'];
    }

 }




//step 2 add  moderator in rooom
 if($newrow_room_id > 0)
{
$newrow_id;
  $newrow_tutor_add[]=  $newrow_id;
  $post = [
  'enroll_users' =>$newrow_tutor_add,

  ];


   $RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$newrow_room_id;
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
    //print_r($result);//exit();

  }




 //step 3 launch borad url  with moderator

if($newrow_room_id > 0 &&  $newrow_id > 0)
{
    $Api_url='https://smart.newrow.com/backend/api/rooms/url/'.$newrow_room_id.'?user_id='.$newrow_id;
    $ch = curl_init($Api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //Set your auth headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
    ));

    // get stringified data/output. See CURLOPT_RETURNTRANSFER
    $data = curl_exec($ch);
    $Board=json_decode($data);
    $info = curl_getinfo($ch);
    // close curl resource to free up system resources
    curl_close($ch);




//print_r($Board);
}
else{


  echo 'oops something went wrong';
}

if(!empty($Board->data->url)){
?>

<!-- add room url in iframe-->
<iframe
  allow="microphone *; camera *; speakers *; usermedia *; autoplay*;" 
  allowfullscreen   
  src="<?php echo $Board->data->url ;?>"  height="100%" width="100%">
</iframe>
<?php } 


?>