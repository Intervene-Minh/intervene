<?php
/**
 * The AuthorizeNet PHP SDK. Include this file in your project.
 *Simon
 * @package AuthorizeNet
 */
/*
 * test cards
 * - American Express Test Card: 370000000000002
- Discover Test Card: 6011000000000012
- Visa Test Card: 4007000000027
- Second Visa Test Card: 4012888818888
- JCB: 3088000000000017
- Diners Club/ Carte Blanche: 38000000000006
 */
define ( "AUTHORIZENET_API_LOGIN_ID","254vVW4tS");// "6q3a5CH6TL" );
define ( "AUTHORIZENET_TRANSACTION_KEY", "55Vky85P6Fr53AWg");//"6582NFupxC9Huz8U" );//
define ( "AUTHORIZENET_SANDBOX", true );
require dirname(__FILE__) . '/shared/AuthorizeNetRequest.php';
require dirname(__FILE__) . '/shared/AuthorizeNetTypes.php';
require dirname(__FILE__) . '/shared/AuthorizeNetXMLResponse.php';
require dirname(__FILE__) . '/shared/AuthorizeNetResponse.php';
require dirname(__FILE__) . '/AuthorizeNetAIM.php';
require dirname(__FILE__) . '/AuthorizeNetARB.php';
require dirname(__FILE__) . '/AuthorizeNetCIM.php';
require dirname(__FILE__) . '/AuthorizeNetSIM.php';
require dirname(__FILE__) . '/AuthorizeNetDPM.php';
require dirname(__FILE__) . '/AuthorizeNetTD.php';
require dirname(__FILE__) . '/AuthorizeNetCP.php';

if (class_exists("SoapClient")) {
    require dirname(__FILE__) . '/AuthorizeNetSOAP.php';
}
/**
 * Exception class for AuthorizeNet PHP SDK.
 *
 * @package AuthorizeNet
 */
class AuthorizeNetException extends Exception
{
}