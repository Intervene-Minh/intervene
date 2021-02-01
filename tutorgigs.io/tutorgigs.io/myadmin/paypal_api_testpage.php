<?php


 function getToken(){


			$ch = curl_init();
			// Live ID
			$clientId="AQJNRDh_tDWoDziE1n5TPT522Cs_9zEhSYWtOhRvbKjcpmydH0RMZOWEwsA_GhoIXMvrQkiLhdodORPp";
			$secret = "EIfyJDiUvkTukV2d6qNm5tNK-Hn0dGSZkSKKuiB6QifX1pp7dMl_sTakaCJB-ChRBDwHjJ24-oWf3lyY";
			$domain = "https://api.paypal.com/v1/oauth2/token";

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

			$get_access_token= $obj->access_token;
			if(empty($result)) { 
			die("Error: No response.");
			}
			else{
			return $get_access_token;
			}
			curl_close($ch); //THIS CODE IS NOW WORKING!

 }


 function requestPayout($get_access_token){

 	global $new_json;  
	$access_token='Bearer '.$get_access_token;
	///// Load Api data////
	$url = 'https://api.paypal.com/v1/payments/payouts';
	$headers_arr=array('Authorization: '.$access_token,'Content-Type: application/json');
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $new_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_arr);
	$result = curl_exec($ch);
	//print_r ($result);
	curl_close($ch);
	return $result;

}

?>