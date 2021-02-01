<?php
 
extract($_REQUEST);
if($action=='answer'){
// set post fields
$post = ['instanceID' =>$instanceID];

$ch = curl_init('http://ec2-35-165-58-67.us-west-2.compute.amazonaws.com/dev/get_course-answer.php');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// execute!
$response = curl_exec($ch);

//print_r($response);
// close the connection, release resources used
curl_close($ch);

/* get  data in json formta*/
$responseData     =     json_decode($response);
echo  $responseData->response;

}
else{

// set post fields
$post = ['instanceID' =>$instanceID];

$ch = curl_init('http://ec2-35-165-58-67.us-west-2.compute.amazonaws.com/dev/get_course-description.php');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// execute!
$response = curl_exec($ch);

//print_r($response);
// close the connection, release resources used
curl_close($ch);

/* get  data in json formta*/
$responseData     =     json_decode($response);


//print_r($responseData);
echo $questionName     =     $responseData->name;
echo $content          =     $responseData->intro;


}
?>

