GRAPIPHPClient Examples
=======================

``` 
<?php
$objGeniusReferralsAPIClient = new GRPHPAPIClient('YOUR_USERNAME', 'YOUR_API_PASSWORD');

//getApiUrl
$strResponse = $objGeniusReferralsAPIClient->getApiUrl();
return $strResponse;

//getRoot
$jsonResponse = $objGeniusReferralsAPIClient->getRoot();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getAccounts
$jsonResponse = $objGeniusReferralsAPIClient->getAccounts();
$jsonResponse = $objGeniusReferralsAPIClient->getAccounts(1, 10, 'name::todd', '-created');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getAccount
$jsonResponse = $objGeniusReferralsAPIClient->getAccount('example-com');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getAdvocates
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocates('example-com');
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocates('example-com', 1, 10, 'name::todd|lastname::smith', 'name|lastname');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//postAdvocate
$aryAdvocate = array('advocate' => array("name" => "Jonh",
        "lastname" => "Smith",
        "email" => "jonh@email.com",
        "payout_threshold" => 10));
$objGeniusReferralsAPIClient->postAdvocate('example-com', $aryAdvocate);
return $objGeniusReferralsAPIClient->getResponseCode();

//getAdvocate
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocate('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//patchAdvocate
$aryAdvocate = array('advocate' => array("name" => "Jonh",
        "lastname" => "Smith",
        "email" => "jonh@email.com",
        "payout_threshold" => 10));
$objGeniusReferralsAPIClient->patchAdvocate('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', $aryAdvocate);
return $objGeniusReferralsAPIClient->getResponseCode();

//getAdvocatePaymentMethods
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocatePaymentMethods('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d');
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocatePaymentMethods('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', 1, 10, 'username::todd', '-created');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//postAdvocatePaymentMethod
$aryPaymentMethod = array("advocate_payment_method" => array("username" => "advocate@email.com",
        "description" => "My main paypal account",
        "is_active" => true));
$objGeniusReferralsAPIClient->postAdvocatePaymentMethod('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', $aryPaymentMethod);
return $objGeniusReferralsAPIClient->getResponseCode();

//getAdvocatePaymentMethod
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocatePaymentMethod('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', 2);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//putAdvocatePaymentMethod
$aryPaymentMethod = array("advocate_payment_method" => array("username" => "advocate@email.com",
        "description" => "My main paypal account",
        "is_active" => true));
$objGeniusReferralsAPIClient->putAdvocatePaymentMethod('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', 2, $aryPaymentMethod);
return $objGeniusReferralsAPIClient->getResponseCode();

//getReferrals
$jsonResponse = $objGeniusReferralsAPIClient->getReferrals('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d');
$jsonResponse = $objGeniusReferralsAPIClient->getReferrals('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', 1, 10, 'referral_origin_slug::facebook-share', '-created');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReferral
$jsonResponse = $objGeniusReferralsAPIClient->getReferral('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', 2);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//postReferral
$aryReferral = array("referral" => array("referred_advocate_token" => "8b3856077b4243700c15d3c75d1cf9866253f643",
        "referral_origin_slug" => "facebook-share",
        "campaign_slug" => "get-10-of-for-90-days",
        "http_referer" => "http://www.geniusreferrals.com"));
$objGeniusReferralsAPIClient->postReferral('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d', $aryReferral);
return $objGeniusReferralsAPIClient->getResponseCode();

//getBonuses
$jsonResponse = $objGeniusReferralsAPIClient->getBonuses('example-com');
$jsonResponse = $objGeniusReferralsAPIClient->getBonuses('example-com', 1, 10, 'name::todd|lastname::smith', 'name|lastname');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//postBonuses
$aryBonus = array("bonus" => array("advocate_token" => "8b3856077b4243700c15d3c75d1cf9866253f643",
        "reference" => "HSY7292D00",
        "amount_of_payments" => 3,
        "payment_amount" => 10));
$objGeniusReferralsAPIClient->postBonuses('example-com', $aryBonus);
return $objGeniusReferralsAPIClient->getResponseCode();

//getBonus
$jsonResponse = $objGeniusReferralsAPIClient->getBonus('example-com', 2);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getBonusesCheckup
$aryBonusesCheckup = array("advocate_token" => "7c4ae87701ef6e6c9ab64941215da6b1f90f5c7a",
    "reference" => "HSY7292D00",
    "amount_of_payments" => 3,
    "payment_amount" => 10);
$jsonResponse = $objGeniusReferralsAPIClient->getBonusesCheckup('example-com', $aryBonusesCheckup);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getBonusesTraces
$jsonResponse = $objGeniusReferralsAPIClient->getBonusesTraces('example-com');
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocates('example-com', 1, 10, 'name::todd|lastname::smith', 'name|lastname');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getBonusesTrace
$jsonResponse = $objGeniusReferralsAPIClient->getBonusesTrace('example-com', 2);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getCampains
$jsonResponse = $objGeniusReferralsAPIClient->getCampains('example-com');
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocates('example-com', 1, 10, 'name::todd|lastname::smith', 'name|lastname');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getCampaign
$jsonResponse = $objGeniusReferralsAPIClient->getCampaign('example-com', 'get-10-of-for-90-days');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getCampaign
$jsonResponse = $objGeniusReferralsAPIClient->getRedemptionRequests('example-com');
$jsonResponse = $objGeniusReferralsAPIClient->getAdvocates('example-com', 1, 10, 'name::todd|lastname::smith', 'name|lastname');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//postRedemptionRequest
$$aryRedemptionRequest = array("redemption_request" => array("advocate_token" => "07c159102f66a63b18d4da39bf91b06bacb7db8d",
        "request_status_slug" => "processing",
        "request_action_slug" => "goods",
        "currency_code" => "USD",
        "amount" => 50,
        "description" => "description",
        "advocates_paypal_username" => "alain@mail.com"));
$objGeniusReferralsAPIClient->postRedemptionRequest('example-com', $aryRedemptionRequest);
return $objGeniusReferralsAPIClient->getResponseCode();

//getRedemptionRequest
$jsonResponse = $objGeniusReferralsAPIClient->getRedemptionRequest('example-com', 2);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//patchRedemptionRequestRedemption
$objGeniusReferralsAPIClient->patchRedemptionRequestRedemption('example-com', 2);
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getBonusesSummaryPerOriginReport
$jsonResponse = $objGeniusReferralsAPIClient->getBonusesSummaryPerOriginReport('07c159102f66a63b18d4da39bf91b06bacb7db8d');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReferralsSummaryPerOriginReport
$jsonResponse = $objGeniusReferralsAPIClient->getReferralsSummaryPerOriginReport('07c159102f66a63b18d4da39bf91b06bacb7db8d');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//testAuthentication
$jsonResponse = $objGeniusReferralsAPIClient->testAuthentication();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getBonusesRedemptionMethods
$jsonResponse = $objGeniusReferralsAPIClient->getBonusesRedemptionMethods();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getBonusRedemptionMethod
$jsonResponse = $objGeniusReferralsAPIClient->getBonusRedemptionMethod('auto-into-credit');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getCurrencies
$jsonResponse = $objGeniusReferralsAPIClient->getCurrencies();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getCurrency
$jsonResponse = $objGeniusReferralsAPIClient->getCurrency('USD');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getRedemptionRequestsActions
$jsonResponse = $objGeniusReferralsAPIClient->getRedemptionRequestsActions();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getRedemptionRequestAction
$jsonResponse = $objGeniusReferralsAPIClient->getRedemptionRequestAction('pay-out');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getRedemptionRequestStatuses
$jsonResponse = $objGeniusReferralsAPIClient->getRedemptionRequestStatuses();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getRedemptionRequestStatus
$jsonResponse = $objGeniusReferralsAPIClient->getRedemptionRequestStatus('requested');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReferralOrigins
$jsonResponse = $objGeniusReferralsAPIClient->getReferralOrigins();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReferralOrigin
$jsonResponse = $objGeniusReferralsAPIClient->getReferralOrigin('facebook-share');
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//deleteAdvocates
$jsonResponse = $objGeniusReferralsAPIClient->deleteAdvocates('example-com');
return $objGeniusReferralsAPIClient->getResponseCode();

//deleteAdvocate
$jsonResponse = $objGeniusReferralsAPIClient->deleteAdvocate('example-com', '07c159102f66a63b18d4da39bf91b06bacb7db8d');
return $objGeniusReferralsAPIClient->getResponseCode();

//getReportsBonusesDailyGiven
$jsonResponse = $objGeniusReferralsAPIClient->getReportsBonusesDailyGiven();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReportsClickDailyParticipation
$jsonResponse = $objGeniusReferralsAPIClient->getReportsClickDailyParticipation();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReportsReferralDailyParticipation
$jsonResponse = $objGeniusReferralsAPIClient->getReportsReferralDailyParticipation();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReportsShareDailyParticipation
$jsonResponse = $objGeniusReferralsAPIClient->getReportsShareDailyParticipation();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

//getReportsTopAdvocates
$jsonResponse = $objGeniusReferralsAPIClient->getReportsTopAdvocates();
$aryResponse = json_decode($jsonResponse);
return $aryResponse;

```