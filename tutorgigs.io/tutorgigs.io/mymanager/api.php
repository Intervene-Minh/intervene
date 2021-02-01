<?php
// $headers[] = 'Accept: application/json';
// $headers[] = "Content-Type: application/x-www-form-urlencoded";
// $headers[] = "Authorization: Basic QVl0X2RKNlFkQlNhUGhrTWx2ZTB2OXJPR1c1SC1CNUtOc25pUTczbkJ6ZjN6MVBkTzZVWXBmeThQcVlOT213ZGZKWGhMOUlNemhnVEpIRkc6RUxVNXd1UWFiRGRPSVlMUndJUUlaMzFyUFA4VmNWNWVYeUhPd3kxalZrcWlMSlAyYXdIOXhSREE2MDhsak44SmlQRFZvdjM1ZERYSDEwd3o="

$ch = curl_init();

$clientId = "AQJNRDh_tDWoDziE1n5TPT522Cs_9zEhSYWtOhRvbKjcpmydH0RMZOWEwsA_GhoIXMvrQkiLhdodORPp";
//$secret = "ELU5wuQabDdOIYLRwIQIZ31rPP8VcV5eXyHOwy1jVkqiLJP2awH9xRDA608ljN8JiPDVov35dDXH10wz";
$secret="EIfyJDiUvkTukV2d6qNm5tNK-Hn0dGSZkSKKuiB6QifX1pp7dMl_sTakaCJB-ChRBDwHjJ24-oWf3lyY";

$domain = "https://api.paypal.com/v1/oauth2/token";

//$domain = "https://api.sandbox.paypal.com/v1/oauth2/token";

curl_setopt($ch, CURLOPT_URL,$domain );
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
// CURLOPT_HTTPHEADER
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
//curl_setopt($ch, CURLOPT_SSLVERSION , 6); 

$result = curl_exec($ch);
   $obj= json_decode($result);

  // print_r($result); die; 

 $get_access_token= $obj->access_token;



 if(empty($result)){
 	die("Error: No response.");
 }else{
 	// 'Acesss Token::';
 	//echo $get_access_token;
echo 	$msg= $get_access_token.'::<br/>'; // die; 
 }

curl_close($ch); //THIS CODE IS NOW WORKING!
////////Proceed for payment option//////

   ////////////

//$access_token='Bearer A21AAGdSqXsngUSAnH_1EmLjuzg757denDV5x0eZ0NSyYMayN-YSp42gCZBo-Vx0lZYOtdi8mT7pt_ZmttX7t1vIpvpi-zytA';

$access_token='Bearer '.$get_access_token;
    ///// Load Api data////
//$domain = "https://api.sandbox.paypal.com/v1/oauth2/token";
// $url = 'https://api.sandbox.paypal.com/v1/payments/payouts';

$url = 'https://api.paypal.com/v1/payments/payouts';

$data = array("first_name" => "First name","last_name" => "last name","email"=>"email@gmail.com","addresses" => array ("address1" => "some address" ,"city" => "city","country" => "CA", "first_name" =>  "Mother","last_name" =>  "Lastnameson","phone" => "555-1212", "province" => "ON", "zip" => "123 ABC" ) );


$headers[] = 'Accept: application/json';
$headers[] = 'Authorization: Bearer A21AAGMlHEpgdv_Fm4smWsm7CwWfdrRz458Mp14mzVnU0S1zIORyX-ATQeni2P9bCdzrk7FPLnuKPynwIg8ZhnznY6zDu08bw';
//$headers[] = "Content-Type: application/x-www-form-urlencoded";




//$postdata = json_encode($data);

$postdata='{
  "sender_batch_header": {
    "sender_batch_id": "1001",
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