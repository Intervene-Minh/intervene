<?php 
//phpinfo(); die; 
error_reporting(-1);
    ini_set('display_errors', 1);
    ////////////

$access_token='Bearer A21AAGdSqXsngUSAnH_1EmLjuzg757denDV5x0eZ0NSyYMayN-YSp42gCZBo-Vx0lZYOtdi8mT7pt_ZmttX7t1vIpvpi-zytA';
    ///// Load Api data////
$domain = "https://api.sandbox.paypal.com/v1/oauth2/token";


$url = 'https://api.sandbox.paypal.com/v1/payments/payouts';

$data = array("first_name" => "First name","last_name" => "last name","email"=>"email@gmail.com","addresses" => array ("address1" => "some address" ,"city" => "city","country" => "CA", "first_name" =>  "Mother","last_name" =>  "Lastnameson","phone" => "555-1212", "province" => "ON", "zip" => "123 ABC" ) );


$headers[] = 'Accept: application/json';
$headers[] = 'Authorization: Bearer A21AAGMlHEpgdv_Fm4smWsm7CwWfdrRz458Mp14mzVnU0S1zIORyX-ATQeni2P9bCdzrk7FPLnuKPynwIg8ZhnznY6zDu08bw';
//$headers[] = "Content-Type: application/x-www-form-urlencoded";




//$postdata = json_encode($data);

$postdata='{
  "sender_batch_header": {
    "sender_batch_id": "23424242",
    "email_subject": "You have a payout!",
    "email_message": "You have received a payout! Thanks for using our service!"
  },
  "items": [
    {
      "recipient_type": "EMAIL",
      "amount": {
        "value": "0",
        "currency": "USD"
      },
      "note": "Thanks for your patronage!",
      "sender_item_id": "123",
      "receiver": "gulshan@srinfosystem.com"
    },
    {
      "recipient_type": "EMAIL",
      "amount": {
        "value": "0",
        "currency": "USD"
      },
      "note": "Thanks for your patronage!",
      "sender_item_id": "345",
      "receiver": "rohit@srinfosystem.com"
    }
    
  ]
}';

 

$headers_arr=array('Authorization: '.$access_token,'Content-Type: application/json');


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);



curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_arr);
$result = curl_exec($ch);

print_r ($result);
curl_close($ch);



?>