<?php 
//phpinfo(); die; 
error_reporting(-1);
    ini_set('display_errors', 1);

// set post fields
$items=array();
$items['studentName']='rohit';
$items['email']='ajay@srinfosystem.com';

$items['mobileNumber']='7299889657';
#
$items['courseId']='98';
$items['cityId']='99';
$items['stateId']='demo';


$items['utm_source']='demo';
$items['utm_medium']='demo';
$items['utm_content']='demo';
$items['utm_campaign']='demo';
$items['utm_network']='demo';
$items['utm_term']='demo';
$items['utm_sitetarget']='7299889657';
$items['url']='demo.com';
$items['ip']='122.160.51.226';

 //print_r($items) ; die; 


 ////////////////////////


 	$post = [
    'studentName' => $items['studentName'],
    'email' => 'ajay@srinfosystem.com',
    'mobileNumber' => '7299889657',
    'courseId' => '98',
    'cityId' => '98',
    'stateId' => '98',
     'utm_source' => 'demo',
      'utm_medium' => 'demo',
      'utm_content' => 'demo',
      'utm_campaign' => 'demo',//##
       'utm_network' => 'demo',
         'utm_term' => 'demo',
          'utm_sitetarget' => 'demo',
           'url' => 'demo',
            'ip' => $items['ip']
    
    
    //'gender'   => 1,
];


$headers[] = 'Accept: application/json';
$headers[] = "Authorization:Basic U2FudG9zaDpTYW50b3NoIUAyMQ==";
$headers[] = "apikey:0a5565ba-5839-48d2-88a4-242286589f9c";
// $headers[] = "apikey:$password";

// $ch = curl_init('http://www.example.com');
$ch = curl_init('https://manipal.edu/bin/manipal/components/generate/thirdpartyrfileads');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute!
$response = curl_exec($ch);

echo 'Result-';
 print_r($response) ; die; 

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
var_dump($response);





    die;

 
?>