<?php
include('inc/connection.php');

session_start();
ob_start();

function curPageName() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

if( !(isset($_SESSION['login_user'])&&isset($_SESSION['login_id'])&&isset($_SESSION['login_role'])) ) {
	if(!(curPageName()=='login.php'||curPageName()=='signup.php')){
		header('Location: login.php');
		exit;
	}
}else{
	if(curPageName()=='login.php'||curPageName()=='signup.php'){
		header("location: index.php");
		exit;
	}
}

include('paypal/paypal.php');
include('paypal/config-paypal.php');

if(isset($_GET['package']) && is_numeric($_GET['package']) && isset($_GET['action']) && $_GET['action']=='checkout'){
	//Get data and send to Paypal
	$package_id = $_GET['package'];
	
	$sql = "SELECT * FROM `packages` WHERE `id`=$package_id AND `id` NOT IN (1) LIMIT 1;";
	$results = mysql_query($sql);

	
	if(mysql_num_rows($results) > 0){
		$item = mysql_fetch_assoc($results);

		//setup paypal
		

		// echo $current_page_url;
		// die();
		$package_name = $item['name'];
		$package_total = $item['price'];
		$amt = $package_total; //because vat = 0;
		//setup SESSION to get it later
		$_SESSION['paypal'] = array();
		$_SESSION['paypal']['item'] = $item;
		
		
		$paypal_data .= '&L_PAYMENTREQUEST_0_NAME0='.urlencode($package_name);
		$paypal_data .= '&L_PAYMENTREQUEST_0_AMT0='.urlencode($package_total);		
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY0='. urlencode(1);
		
		$padata = 	'&METHOD=SetExpressCheckout'.
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				$paypal_data.				
				'&NOSHIPPING=0'.	# Set 1 to hide buyer's shipping address, in-case products that does not require shipping
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($package_total).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($taxamt).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_cost).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($amt).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&SOLUTIONTYPE=sole'.
				'&LANDINGPAGE=Billing'.
				'&LOCALECODE=US'.	# PayPal pages to match the language on your website.
				// '&LOGOIMG=http://www.sanwebe.com/wp-content/themes/sanwebe/img/logo.png'.	# Site logo
				'&CARTBORDERCOLOR=FFFFFF'.	// Border color of cart
				'&ALLOWNOTE=1';
				
				
				
				
		$paypal = new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		// Respond according to message we receive from Paypal
		if( "SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]) ) {
			$_SESSION['paypal']['token'] = urldecode($httpParsedResponseAr["TOKEN"]);
			// Redirect user to PayPal store with Token received.
			$paypalurl = 'https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
			header('Location: '.$paypalurl);
			exit();
		} else {
			// Show error message
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
		}
		
		
	}else{
		echo 'Not have this package!';
	}
	
}


// Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if( isset($_GET["token"]) && isset($_GET["PayerID"]) && isset($_SESSION['paypal']) && $_GET["token"]==$_SESSION['paypal']['token']) {
	// We will be using these two variables to execute the "DoExpressCheckoutPayment"
	// Note: we haven't received any payment yet.
	
	$token		= $_GET["token"];
	$payer_id	= $_GET["PayerID"];

	$package_name = $_SESSION['paypal']['item']['name'];
	$package_total = $_SESSION['paypal']['item']['price'];
	$amt = $package_total; //because vat = 0;
	
	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME0='.urlencode($package_name);
	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT0='.urlencode($package_total);		
	$paypal_data .= '&L_PAYMENTREQUEST_0_QTY0='. urlencode(1);
		
	$padata = 	'&TOKEN='.urlencode($token).
				'&PAYERID='.urlencode($payer_id).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				$paypal_data.				
				'&NOSHIPPING=0'.	# Set 1 to hide buyer's shipping address, in-case products that does not require shipping
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($package_total).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($taxamt).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_cost).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($amt).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'';

	// We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
	$paypal= new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
	// Check if everything went ok..
	if( "SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]) ) {
		
		if( 'Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"] || 'Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
			$success_pay = true;
		}

		// We can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
		// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
		$padata = 	'&TOKEN='.urlencode($token);
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

			// echo '<br /><b>Stuff to store in database :</b><br />';
			
			#### SAVE BUYER INFORMATION IN DATABASE ###
			// See (http://www.sanwebe.com/2013/03/basic-php-mysqli-usage) for mysqli usage
			// Use urldecode() to decode url encoded strings.
			
			$user_id = $_SESSION['login_id'];
			$status = 1;	//0:pending 1:paid 2:cancelled
			$package_id = $_SESSION['paypal']['item']['id'];
			$package_info = serialize($_SESSION['paypal']['item']);
			$transection_code = $httpParsedResponseAr["TRANSACTIONID"];
			$paypal_info = serialize($httpParsedResponseAr);
			//$vat = 0.00; //have setup in config
			$limited = $_SESSION['paypal']['item']['limited'];
			$role = $package_id;//1: FreeUser 2:LimitedQuestion 3:UnlimitedQuestion
			
			//
			unset($_SESSION['paypal']);
			
			$sql = "
				INSERT INTO `orders` (`user_id`,`status`,`package_id`,`package_info`,`transection_code`,`paypal_info`,`vat`)
				VALUES ($user_id, $status, $package_id, '$package_info', '$transection_code', '$paypal_info', $vat);
			";
			$return = mysql_query($sql);
			
			include('inc/update-user-question.php');//include function updateQuestionsRemaining
			$return_update = updateQuestionsRemaining($user_id,$limited,$role);//update in table users
			
			if($return && $return_update){
				$_SESSION['login_role'] = $role;
				header('Location: profile.php');
				exit;
			}else{
				print('<script>alert("Error!");</script>');
				header('Location: membership.php');
				exit;
			}
		
		} else {
			echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
		}
	} else {
		echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
	}
}
if(isset($_GET['action']) && $_GET['action']=='cancel'){
	unset($_SESSION['paypal']);
	header('Location: membership.php');
	exit;
}




?>