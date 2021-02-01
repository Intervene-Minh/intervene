<?php



//include("header.php"); 
include('newrow.functions.php');
$warning_msg=[];
$success_msg=[];

$getToken=_get_token(); 
$_SESSION['ses_admin_token']=$getToken;

if(!isset($_SESSION['ses_teacher_id']))
{
    exit('Tutor id missig, ?uid=34435');
}

$tutorId=$_SESSION['ses_teacher_id'];

$Tutor=mysql_fetch_assoc(mysql_query(" SELECT * FROM  gig_teachers WHERE id = '$tutorId' "));

if(isset($Tutor)&&!empty($Tutor))
{
    

    $res_newrow=mysql_query(" SELECT * FROM  newrow_x_tutors WHERE tutor_intervene_id= '$tutorId' ");
    $Tutor_rows=mysql_num_rows($res_newrow);

    if($Tutor_rows>=1)
    {
      
      $Tutor_newrow=mysql_fetch_assoc($res_newrow);
      $warning_msg[]='newrow id already Created!';

    }elseif($Tutor_rows<1)
    {
      $canCreateNewrowId='yes';
      $success_msg[]='New newrow ID Created, for tutor id-'.$tutorId; 
    }

 }

 else
 {
   $warning_msg[]='Wrong user id! ';

 }

if(isset($canCreateNewrowId)&&$canCreateNewrowId=='yes')
  {
    $userId=time();
    $get_user_email = (string)$Tutor['email'];
    $post = [
        'user_name' =>'Tutor_'.$userId,
        'user_email' =>$get_user_email, //'test11@gmail.com',
        'first_name' =>$Tutor['f_name'], //'Mastertutor',
        'last_name' =>'Tutor',
        'role' =>'Instructor', // Instructor | Student {CompanyUser}
    ];

$token=$getToken;// // 5fc8c417a486296fb3fc334293b2b54c

if(empty($token))
{
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
       ///////////////////////////

      $Tutor_int_id=$tutorId;
      $UserEmail=$Tutor['email'];
      $newrow_user_id=$user_row->data->user_id;
      $sql="INSERT INTO newrow_x_tutors SET tutor_intervene_id='$Tutor_int_id',newrow_email='$UserEmail',
      newrow_username='$UserEmail',newrow_ref_id='$newrow_user_id'";

      $Add=mysql_query($sql);

     }

      // return json_decode($result); // Return the received data
     // New newrow ID Created
    //  $_SESSION['msg_success'] = 'Welcome';
     // header("Location:home.php"); exit;
     /////////////////
     if(!empty($success_msg))
     {
       // echo implode(',<br/>', $success_msg ); //print_r($success_msg);
        //echo '<br/>';
     }
     /////////////////////

      if(!empty($warning_msg))
      {

        //echo implode(',<br/>', $warning_msg );
      }

?>

