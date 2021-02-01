<?php

namespace GeniusReferrals\Tests;

/**
 * Requires
 */
require_once __DIR__ . '/../../../../../../vendor/autoload.php';

use GeniusReferrals\GRPHPAPIClient;

class GRPHPAPIClientTest extends \PHPUnit_Framework_TestCase {

    protected $objWebClient;

    public function setUp() {

        $this->objWebClient = new GRPHPAPIClient('YOUR_USERNAME', 'YOUR_API_TOKEN');
    }

    /*
     * Testing getRoot
     */

    public function testGetRoot() {

        $this->objWebClient->getRoot();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing testAuthentication
     */

    public function testAuthentication() {

        $this->objWebClient->testAuthentication();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAccounts
     */

    public function testGetAccounts() {

        $this->objWebClient->getAccounts();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAccount
     */

    public function testGetAccount() {

        $this->objWebClient->getAccount('example-com');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAdvocates
     */

    public function testGetAdvocates() {

        $this->objWebClient->getAdvocates('example-com');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAdvocate
     */

    public function testGetAdvocate() {

        $jsonResponse = $this->objWebClient->getAdvocates('example-com', 1, 1);

        $this->objWebClient->getAdvocate('example-com', json_decode($jsonResponse)->data->results[0]->token);

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAdvocatePaymentMethods
     */

    public function testGetAdvocatePaymentMethods() {

        $this->objWebClient->getAdvocatePaymentMethods('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAdvocatePaymentMethod
     */

    public function testGetAdvocatePaymentMethod() {

        $jsonResponse = $this->objWebClient->getAdvocatePaymentMethods('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', 1, 1);

        $this->objWebClient->getAdvocatePaymentMethod('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', json_decode($jsonResponse)->data->results[0]->id);

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getReferrals
     */

    public function testGetReferrals() {

        $this->objWebClient->getReferrals('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getReferral
     */

    public function testGetReferral() {

        $jsonResponse = $this->objWebClient->getReferrals('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', 1, 1);

        $this->objWebClient->getReferral('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', json_decode($jsonResponse)->data->results[0]->id);

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonuses
     */

    public function testGetBonuses() {

        $this->objWebClient->getBonuses('example-com');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonus
     */

    public function testGetBonus() {

        $jsonResponse = $this->objWebClient->getBonuses('example-com', 1, 1);

        $this->objWebClient->getBonus('example-com', json_decode($jsonResponse)->data->results[0]->id);

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonusesTraces
     */

    public function testGetBonusesTraces() {

        $this->objWebClient->getBonusesTraces('example-com');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonusesTrace
     */

    public function testGetBonusesTrace() {

        $jsonResponse = $this->objWebClient->getBonusesTraces('example-com');

        $this->objWebClient->getBonusesTrace('example-com', json_decode($jsonResponse)->data->results[0]->id);

        $this->assertEquals($this->objWebClient->getStatusCode(), 200);
    }

    /*
     * Testing getCampaigns
     */

    public function testGetCampaigns() {

        $this->objWebClient->getCampaigns('example-com');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getCampaign
     */

    public function testGetCampaign() {

        $this->objWebClient->getCampaign('example-com', 'get-10-of-for-90-days');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getRedemptionRequests
     */

    public function testGetRedemptionRequests() {

        $this->objWebClient->getRedemptionRequests('example-com');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getRedemptionRequests
     */

    public function testGetRedemptionRequest() {

        $jsonResponse = $this->objWebClient->getRedemptionRequests('example-com', 1, 1);

        $this->objWebClient->getRedemptionRequest('example-com', json_decode($jsonResponse)->data->results[0]->id);

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonusesSummaryPerOriginReport
     */

    public function testGetBonusesSummaryPerOriginReport() {

        $this->objWebClient->getBonusesSummaryPerOriginReport('767d478bd662cfe419e1ff6e70c0a7d723493c8b');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getReferralsSummaryPerOriginReport
     */

    public function testGetReferralsSummaryPerOriginReport() {

        $this->objWebClient->getReferralsSummaryPerOriginReport('767d478bd662cfe419e1ff6e70c0a7d723493c8b');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonusesRedemptionMethods
     */

    public function testGetBonusesRedemptionMethods() {

        $this->objWebClient->getBonusesRedemptionMethods();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getBonusRedemptionMethod
     */

    public function testGetBonusesRedemptionMethod() {

        $this->objWebClient->getBonusRedemptionMethod('auto-into-credit');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getCurrencies
     */

    public function testGetCurrencies() {

        $this->objWebClient->getCurrencies();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getCurrency
     */

    public function testGetCurrency() {

        $this->objWebClient->getCurrency('USD');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getRedemptionRequestsActions
     */

    public function testGetRedemptionRequestsActions() {

        $this->objWebClient->getRedemptionRequestsActions();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getRedemptionRequestAction
     */

    public function testGetRedemptionRequestsAction() {

        $this->objWebClient->getRedemptionRequestAction('pay-out');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getRedemptionRequestStatuses
     */

    public function testGetRedemptionRequestStatuses() {

        $this->objWebClient->getRedemptionRequestStatuses();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getRedemptionRequestStatus
     */

    public function testGetRedemptionRequestStatus() {

        $this->objWebClient->getRedemptionRequestStatus('requested');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getReferralOrigins
     */

    public function testGetReferralOrigins() {

        $this->objWebClient->getReferralOrigins();

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getReferralOrigin
     */

    public function testGetReferralOrigin() {

        $this->objWebClient->getReferralOrigin('facebook-share');

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing getAdvocatesShareLinks
     */

//    public function testGetAdvocatesShareLinks() {
//
//        $this->objWebClient->getAdvocatesShareLinks('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b');
//
//        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
//    }

    /*
     * Testing getBonusesCheckup
     */

    public function testGetBonusesCheckup() {

        $aryBonus = array("advocate_token" => "767d478bd662cfe419e1ff6e70c0a7d723493c8b",
            "reference" => "HSY7292D00",
            "amount_of_payments" => 3,
            "payment_amount" => 10);
        $this->objWebClient->getBonusesCheckup('example-com', $aryBonus);

        $this->assertEquals($this->objWebClient->getResponseCode(), 200);
    }

    /*
     * Testing postAdvocate
     */

    public function testPostAdvocate() {

        $aryAdvocate = array('advocate' => array("name" => "test",
                "lastname" => "test",
                "email" => "test@dasda.sdasda",
                "payout_threshold" => 12));
        $objResponse = $this->objWebClient->postAdvocate('example-com', $aryAdvocate);
        $this->assertEquals($this->objWebClient->getResponseCode(), 201, false);

        $strLocation = $objResponse->getLocation();

        $arrHeaders = $this->getHeaders();

        $objClient = $this->objWebClient->getWebClient();
        $objRequest = $objClient->get($strLocation, $arrHeaders);
        $objResponse = $objRequest->send();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing patchAdvocate
     */

    public function testPatchAdvocate() {

        $aryAdvocate = array("name" => "Jonh",
            "lastname" => "Smith",
            "email" => "jonh@email.com",
            "payout_threshold" => 10);
        $objResponse = $this->objWebClient->patchAdvocate('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', $aryAdvocate);

        $this->assertEquals($this->objWebClient->getResponseCode(), 204);
    }

    /*
     * Testing postAdvocatePaymentMethod
     */

    public function testPostAdvocatePaymentMethod() {

        $aryPaymentMethod = array("advocate_payment_method" => array("username" => "advocate@email.com",
                "description" => "My main paypal account",
                "is_active" => true));
        $objResponse = $this->objWebClient->postAdvocatePaymentMethod('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', $aryPaymentMethod);
        $this->assertEquals($this->objWebClient->getResponseCode(), 201, false);

        $strLocation = $objResponse->getLocation();

        $arrHeaders = $this->getHeaders();

        $objClient = $this->objWebClient->getWebClient();
        $objRequest = $objClient->get($strLocation, $arrHeaders);
        $objResponse = $objRequest->send();
        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing putAdvocatePaymentMethod
     */

    public function testPutAdvocatePaymentMethod() {

        $jsonResponse = $this->objWebClient->getAdvocatePaymentMethods('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', 1, 1);
        $aryPaymentMethod = array("advocate_payment_method" => array("username" => "advocate@email.com",
                "description" => "My main paypal account",
                "is_active" => true));
        $objResponse = $this->objWebClient->putAdvocatePaymentMethod('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', json_decode($jsonResponse)->data->results[0]->id, $aryPaymentMethod);

        $this->assertEquals($this->objWebClient->getResponseCode(), 204);
    }

    /*
     * Testing postReferral
     */

    public function testPostReferral() {

        $aryReferral = array("referral" => array("referred_advocate_token" => "b1535f8b55985916f0049156065908f349b4a2ee",
                "referral_origin_slug" => "facebook-share",
                "campaign_slug" => "get-10-of-for-90-days",
                "http_referer" => "http://www.geniusreferrals.com"));
        $objResponse = $this->objWebClient->postReferral('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b', $aryReferral);
        $this->assertEquals($this->objWebClient->getResponseCode(), 201, false);

        $strLocation = $objResponse->getLocation();

        $arrHeaders = $this->getHeaders();

        $objClient = $this->objWebClient->getWebClient();
        $objRequest = $objClient->get($strLocation, $arrHeaders);
        $objResponse = $objRequest->send();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing postBonuses
     */

    public function testPostBonus() {

        $aryBonus = array("bonus" => array("advocate_token" => "b1535f8b55985916f0049156065908f349b4a2ee",
                "reference" => "HSY7292D00",
                "amount_of_payments" => 3,
                "payment_amount" => 10));
        $objResponse = $this->objWebClient->postBonuses('example-com', $aryBonus);
        $this->assertEquals($this->objWebClient->getResponseCode(), 201, false);

        $strLocation = $objResponse->getLocation();

        $arrHeaders = $this->getHeaders();

        $objClient = $this->objWebClient->getWebClient();
        $objRequest = $objClient->get($strLocation, $arrHeaders);
        $objResponse = $objRequest->send();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing postRedemptionRequest
     */

//    public function testPostRedemptionRequest() {
//
//        $aryRedemptionRequest = array("redemption_request" => array("advocate_token" => "767d478bd662cfe419e1ff6e70c0a7d723493c8b",
//                "request_status_slug" => "processing",
//                "request_action_slug" => "goods",
//                "currency_code" => "EUR",
//                "amount" => 3,
//                "description" => "credit",
//                "advocates_paypal_username" => "alainhl@gmail.com"));
//        $objResponse = $this->objWebClient->postRedemptionRequest('example-com', $aryRedemptionRequest);
//        $this->assertEquals($this->objWebClient->getResponseCode(), 201, false);
//
//        $strLocation = $objResponse->getLocation();
//
//        $arrHeaders = $this->getHeaders();
//
//        $objClient = $this->objWebClient->getWebClient();
//        $objRequest = $objClient->get($strLocation, $arrHeaders);
//        $objResponse = $objRequest->send();
//
//        $this->assertEquals($objResponse->getStatusCode(), 200);
//    }

    /*
     * Testing patchRedemptionRequestRedemption
     */

    public function testPatchRedemptionRequestRedemption() {

        $objResponse = $this->objWebClient->patchRedemptionRequestRedemption('example-com', 1);

        $this->assertEquals($objResponse->getStatusCode(), 204);
    }

    /*
     * Testing deleteAdvocates
     */

    public function testDeleteAdvocates() {

        $objResponse = $this->objWebClient->deleteAdvocates('example-com');

        $this->assertEquals($objResponse->getStatusCode(), 204);
    }

    /*
     * Testing deleteAdvocate
     */

    public function testDeleteAdvocate() {

        $objResponse = $this->objWebClient->deleteAdvocate('example-com', '767d478bd662cfe419e1ff6e70c0a7d723493c8b');

        $this->assertEquals($objResponse->getStatusCode(), 204);
    }

    /*
     * Testing getReportsBonusesDailyGiven
     */

    public function testGetReportsBonusesDailyGiven() {

        $objResponse = $this->objWebClient->getReportsBonusesDailyGiven();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing getReportsClickDailyParticipation
     */

    public function testGetReportsClickDailyParticipation() {

        $objResponse = $this->objWebClient->getReportsClickDailyParticipation();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing getReportsReferralDailyParticipation
     */

    public function testGetReportsReferralDailyParticipation() {

        $objResponse = $this->objWebClient->getReportsReferralDailyParticipation();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing getReportsShareDailyParticipation
     */

    public function testGetReportsShareDailyParticipation() {

        $objResponse = $this->objWebClient->getReportsShareDailyParticipation();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    /*
     * Testing getReportsTopAdvocates
     */

    public function testGetReportsTopAdvocates() {

        $objResponse = $this->objWebClient->getReportsTopAdvocates();

        $this->assertEquals($objResponse->getStatusCode(), 200);
    }

    private function getHeaders() {
        $arrHeaders = array(
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        );
        return $arrHeaders;
    }

}
