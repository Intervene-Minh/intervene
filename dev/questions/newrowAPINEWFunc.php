   <?php
    include('inc/connection.php'); 
    session_start();
    ob_start();
    $canGetRoomAccess=0;
    if(!isset($_SESSION['live'])){

      exit('Curr Live Session ID not found! ');
    }
    
    $today = date("Y-m-d H:i:s"); // 
    $success_msg=[];
    include('libraries/newrow.functions.php');
    $currToken=_get_token();


    /* CREATE USER FUNCTION ON NEW ROW API*/
    function _create_user($role_type='student',$user_arr=array())
    {

              global $currToken;
              $post = [
              'user_name' =>$user_arr['user_name'], 
              'user_email' =>$user_arr['email'],
              'first_name' =>$user_arr['first_name'],
              'last_name' =>$role_type,
              'CompanyUser' =>$role_type, 
              ];
             $token=$currToken;

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
              return $user_row; 
}

/*ADD /ENROLL STUDETN IN NEWROW ROOM*/
function EnrollStudnenINClass($stuArr,$roomID)
{

              global $currToken;

              $post = [ 'enroll_users' =>$stuArr];

              $RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$roomID;
              $ch = curl_init($RoomUrlLink); // Initialise cURL
              $post = json_encode($post); // Encode the data array into a JSON string
              $authorization = "Authorization: Bearer ".$currToken; // Prepare the authorisation token
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

                return $result->status;

                    }

}
?>