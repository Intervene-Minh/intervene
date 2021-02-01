<?php
function isSSL() { return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443; }
	
$http = 'http://';
if(isSSL()){
	$http = 'https://';
}

$current_page_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];


$PayPalMode 			= 'live'; // sandbox or live
$PayPalApiUsername 		= 'support_api1.lonestaar.com';										// PayPal API Username
$PayPalApiPassword 		= 'T7Q995DNKVNMREP5';												// Paypal API password
$PayPalApiSignature 	= 'AbsZ2kPs.u8nANAPmWfrfj6-8iPHA01q5xE8a74Jywy4.V2wxPyYi1TT';		// Paypal API Signature
$PayPalCurrencyCode 	= 'USD';															// Paypal Currency Code
$PayPalReturnURL 		= $current_page_url;			// Point to paypal-express-checkout page
$PayPalCancelURL 		= $current_page_url.'?action=cancel';				// Cancel URL if user clicks cancel

// Additional taxes and fees
$HandalingCost 		= 0.00;		// Handling cost for the order.
$InsuranceCost 		= 0.00;		// Shipping insurance cost for the order.
$shipping_cost      = 0.00;		// Shipping cost
$ShippinDiscount 	= 0.00;		// Shipping discount for this order. Specify this as negative number (eg -1.00)
$vat = 0;
$amt = 0;
$taxamt = 0;
$paypal_data = '';

?>