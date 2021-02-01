<?php
 error_reporting(-1);
 ini_set('display_errors', 1);
# https://smart.newrow.com/backend/api/users/123

//////////////////////
$curl = curl_init();


curl_setopt_array($curl, array(
 CURLOPT_URL => "https://smart.newrow.com/backend/api/users/",
 CURLOPT_RETURNTRANSFER => true,
 CURLOPT_ENCODING => "",
 CURLOPT_MAXREDIRS => 10,
 CURLOPT_TIMEOUT => 30,
 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 CURLOPT_CUSTOMREQUEST => "PUT",
 CURLOPT_POSTFIELDS => "first_name=Himash&last_name=Ver",
 CURLOPT_HTTPHEADER => array(
 "authorization: Bearer a83fbec…….d0202", "content-type: application/x-www-form-urlencoded",
 ),
));


$response = curl_exec($curl);

print_r($response); die; 



//////////////////////////
curl_close($curl);
$response = json_decode($response);
if ($response->status == "success") {
 // User info has been update
} else {
 // Error occurred
}

?>