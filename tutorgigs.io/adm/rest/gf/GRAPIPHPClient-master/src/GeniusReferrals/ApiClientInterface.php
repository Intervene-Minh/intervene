<?php

namespace GeniusReferrals;

use Guzzle\Http\Message\Response;

/**
 *
 * @author Genius Referrals
 */
interface ApiClientInterface {

    public function __construct($strUsername, $strApiToken);

    public function generateWSSEHeader($strUsername, $strApiToken);

    public function getWebClient();

    public function getApiUrl();

    public function addCommonFilters($strUri, $intPage, $intLimit, $strFilter, $strSort);

    public function getResponseCode();

    public function getRoot();

    public function getAccounts($intPage, $intLimit, $strFilter, $strSort);

    public function getAccount($strAccountSlug);

    public function getAdvocates($strAccountSlug, $intPage, $intLimit, $strFilter, $strSort);

    public function postAdvocate($strAccountSlug, array $arrParams);

    public function getAdvocate($strAccountSlug, $strAdvocateToken);

    public function patchAdvocate($strAccountSlug, $strAdvocateToken, array $arrParams);

    public function getAdvocatePaymentMethods($strAccountSlug, $strAdvocateToken, $intPage, $intLimit, $strFilter, $strSort);

    public function postAdvocatePaymentMethod($strAccountSlug, $strAdvocateToken, array $arrParams);

    public function getAdvocatePaymentMethod($strAccountSlug, $strAdvocateToken, $intAdvocatePaymentMethodId);

    public function putAdvocatePaymentMethod($strAccountSlug, $strAdvocateToken, $intAdvocatePaymentMethodId, array $arrParams);

    public function getReferrals($strAccountSlug, $strAdvocateToken, $intPage, $intLimit, $strFilter, $strSort);

    public function getReferral($strAccountSlug, $strAdvocateToken, $intReferralId);

    public function postReferral($strAccountSlug, $strAdvocateToken, array $arrParams);

    public function getBonuses($strAccountSlug, $intPage, $intLimit, $strFilter, $strSort);

    public function postBonuses($strAccountSlug, array $arrParams);
    
    public function postForceBonuses($strAccountSlug, array $arrParams);

    public function getBonus($strAccountSlug, $intBonusId);

    public function getBonusesCheckup($strAccountSlug, array $arrParams);

    public function getBonusesTraces($strAccountSlug, $intPage, $intLimit, $strFilter, $strSort);

    public function getBonusesTrace($strAccountSlug, $intTraceId);

    public function getCampaigns($strAccountSlug, $intPage, $intLimit, $strFilter, $strSort);

    public function getCampaign($strAccountSlug, $strCampaignSlug);

    public function getRedemptionRequests($strAccountSlug, $intPage, $intLimit, $strFilter, $strSort);

    public function postRedemptionRequest($strAccountSlug, array $arrParams);

    public function getRedemptionRequest($strAccountSlug, $intRedemptionRequestId);

    public function patchRedemptionRequestRedemption($strAccountSlug, $intRedemptionRequestId);

    public function getBonusesSummaryPerOriginReport($strAdvocateToken);

    public function getReferralsSummaryPerOriginReport($strAdvocateToken);

    public function testAuthentication();

    public function getBonusesRedemptionMethods();

    public function getBonusRedemptionMethod($strBonusRedemptionMethodSlug);

    public function getCurrencies();

    public function getCurrency($strCode);

    public function getRedemptionRequestsActions();

    public function getRedemptionRequestAction($strRedemptionRequestActionSlug);

    public function getRedemptionRequestStatuses();

    public function getRedemptionRequestStatus($strRedemptionRequestStatusSlug);
    
    public function getReferralOrigins();

    public function getReferralOrigin($strReferralOriginSlug);
    
    public function getAdvocatesShareLinks($strAccountSlug, $strAdvocateToken);
    
    public function deleteAdvocates($strAccountSlug);
    
    public function deleteAdvocate($strAccountSlug, $strAdvocateToken);
    
    public function getReportsBonusesDailyGiven($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '');
    
    public function getReportsClickDailyParticipation($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '');
    
    public function getReportsReferralDailyParticipation($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '');
    
    public function getReportsShareDailyParticipation($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '');
    
    public function getReportsTopAdvocates($strAccountSlug = '', $strCampaignSlug = '', $intLimit = 10, $strFrom = '', $strTo = '');
}
