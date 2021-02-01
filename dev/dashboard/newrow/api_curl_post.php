<?php 


//phpinfo(); die; 
error_reporting(-1);
ini_set('display_errors', 1);



 	
##############################
 $get_token_id="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJFdmVudFVuaXF1ZUNvZGUiOiJUQjVEIiwiRm9ybVVuaXF1ZUNvZGUiOiJGT1JNNDUiLCJFeGhpYml0b3JFbWFpbCI6InJvaGl0SzQ0N0BnbWFpbC5jb20iLCJGb3JtU3RhdHVzIjoiU3VjY2VzcyJ9.goVCISSjlFtEcI3vbdF2acavVzyQNyowVnv5mUXHvbI";

 ///
$post = [
    'EPTOKEN' =>$get_token_id,
    'order_id' =>19999,
    
    
    
    //'gender'   => 1,
];



// $headers[] = 'Accept: application/json';
// $headers[] = "Authorization:Basic U2FudG9zaDpTYW50b3NoIUAyMQ==";
// $headers[] = "apikey:0a5565ba-5839-48d2-88a4-242286589f9c";
// $headers[] = "apikey:$password";

$ch = curl_init('http://mmmc.drupalservices.io/sendtoken.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //  return 1 with json if true. 
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute!
$response = curl_exec($ch);
  //echo 'Result-';
 print_r($response) ; die; 

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response

?>