<?php 


//phpinfo(); die; 
error_reporting(-1);
ini_set('display_errors', 1);

$post = [
    'user_name' =>'ajaytest@gmail.com',
    'user_email' =>'ajaytest@gmail.com',
    'first_name' =>'Ajay',
     'last_name' =>'Stu',
    // 'role' =>'Student',
    
    
    
    //'gender'   => 1,
];



 $headers[] = 'Accept: application/json';
// $headers[] = "Authorization:Basic U2FudG9zaDpTYW50b3NoIUAyMQ==";
// $headers[] = "apikey:0a5565ba-5839-48d2-88a4-242286589f9c";
// $headers[] = "apikey:$password";

$ch = curl_init('https://smart.newrow.com/backend/api/users/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //  return 1 with json if true. 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$token='f9967788c74a4161ddd4fe6d9dcdf2e4';
//Set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   //'Content-Type: application/json',
   'Authorization: Bearer ' . $token
   ));
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute!
$response = curl_exec($ch);
  //echo 'Result-';
 print_r($response) ; die; 

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response

?>