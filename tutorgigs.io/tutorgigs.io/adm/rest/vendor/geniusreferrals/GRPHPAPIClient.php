<?php

namespace GeniusReferrals;

use Guzzle\Http\Client;
use Devster\Guzzle\Plugin\Wsse\WssePlugin;
use Guzzle\Http\Message\Response;

/*
 * GRAPIPHPClient is the PHP client to consume the Genius Referrals RESTful API.
 *
 * Genius Referrals is a referral marketing platform and word of mouth platform that allows any
 * company/person with a Website to increase his costumer acquisition by using word of mouth techniques
 * thought Facebook, Twitter, Google +, Pinterest, Linkedin, Email and personal recommendations.
 *
 * For more information about Genius referrals please visit www.geniusreferrals.com
 *
 * @author Genius Referrals Team <dev@geniusreferrals.com>
 */

class GRPHPAPIClient implements ApiClientInterface {

    /** @var Client Guzzle client */
    protected $objClient = null;

    /** @var string Client username */
    protected $strUsername;

    /** @var string Client API Token */
    protected $strApiToken;

    /** @var Response */
    protected $objResponse;

    /**
     * Create a new GRPHPAPIClient.
     *
     * @param string $strUsername
     * @param string $strApiToken
     */
    public function __construct($strUsername, $strApiToken) {
        $this->strUsername = $strUsername;
        $this->strApiToken = $strApiToken;
    }

    /**
     * Sign all requests with the WssePlugin.
     *
     * @param string $strUsername Client email
     * @param string $strApiToken Client API Token
     * @return WssePlugin
     */
    public function generateWSSEHeader($strUsername, $strApiToken) {
        $objWssePlugin = new WssePlugin(array(
            'username' => $strUsername,
            'password' => $strApiToken
        ));
        return $objWssePlugin;
    }

    /**
     * Get the Guzzle HTTP client object.
     *
     * @return Client Guzzle HTTP client
     * @see    Guzzle\Http\Client
     */
    public function getWebClient() {
        if ($this->objClient == null) {
            $this->objClient = new Client();

            $objWssePlugin = $this->generateWSSEHeader($this->strUsername, $this->strApiToken);
            $this->objClient->addSubscriber($objWssePlugin);
            $this->objClient->setSslVerification(false);
        }
        return $this->objClient;
    }

    /**
     * Returns the API URL
     *
     * @return string API URL
     */
    public function getApiUrl() {
        if (file_exists(__DIR__ . '/config.php')) {
            require __DIR__ . '/config.php';
            return $apiConfig['api_url'];
        }
    }

    /**
     * Add common filters to a given API URI.
     *
     * @param string $strUri. API URI
     * @param integer page.  The current page, default is 1.
     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Use sort query-string parameter that contains a delimited set of property names.
     *                           For each property name, sort in ascending order, and for each property prefixed with a dash ('-')
     *                           sort in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function addCommonFilters($strUri, $intPage = null, $intLimit = null, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get the response code from the previous request.
     *
     * @return integer
     */
    public function getResponseCode() {
        return $this->objResponse->getStatusCode();
    }

    /**
     * Welcome to Genius Referral Core API.
     *
     * @return string
     */
    public function getRoot() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get the list of client accounts.
     *
     * @param integer $intPage.  The current page, default is 1.
     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: name. Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: name, created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getAccounts($intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get an account by a given slug.
     *
     * @param string $strAccountSlug. The client account slug
     * @return string
     */
    public function getAccount($strAccountSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get the list of  advocates.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer $intPage.  The current page, default is 1.
     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: name, lastname, email, bonus_exchange_method_slug, campaign_slug, from, to, created.
     *                           Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: name, lastname, email, created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getAdvocates($strAccountSlug, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Create a new Genius Referral advocate.
     *
     * @param string $strAccountSlug. The client account slug
     * @param array $arrParams
     * Request Format All parameters in the content of the request are mandatory.
     * {
     *       "advocate":{
     *           "name":"Jonh",
     *           "lastname":"Smith",
     *           "email": "jonh_at_email.com",
     *           "payout_threshold":10
     *       }
     * }
     * @return string
     */
    public function postAdvocate($strAccountSlug, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->post($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get an Genius Referrals advocate.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @return string
     */
    public function getAdvocate($strAccountSlug, $strAdvocateToken) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Update partial elements of an advocate. Use this method when you do not need to update all the elements at the same time.
     * Allowed parameters to be updated are: name, lastname, email, payout_threshold, claimed_balance, unclaimed_balance,
     * campaign_slug, currency_code, advocate_referrer_token.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param array $arrParams
     * Request Format
     * {
     *   "name":"Jonh",
     *   "lastname":"Smith",
     *   "email": "jonh_at_email.com",
     *   "payout_threshold":10,
     *   advocate_referrer_token=4f31f3470dca3161b4f3f14a8c67ac1e56dc93d1
     * }
     * -- OR --
     * {"name":"Jonh"}
     * -- OR send a query string like this --
     * payout_threshold=10&claimed_balance=10&unclaimed_balance=10&campaign_slug=get-10-of-for-90-days&currency_code=EUR
     * @return string
     */
    public function patchAdvocate($strAccountSlug, $strAdvocateToken, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->patch($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get the advocate's payment methods.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param integer page.  The current page, default is 1.

     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: username, is_active. Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: username, created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getAdvocatePaymentMethods($strAccountSlug, $strAdvocateToken, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/payment-methods';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Create a new payment method.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param array $arrParams
     * Request Format All parameters in the content of the request are mandatory.
     * {
     *       "advocate_payment_method":{
     *           "username":"advocate_at_email.com",
     *           "description":"My main paypal account",
     *           "is_active": true
     *       }
     * }
     * @return string
     */
    public function postAdvocatePaymentMethod($strAccountSlug, $strAdvocateToken, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/payment-methods';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->post($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get an advocate's payment method.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param integer $intAdvocatePaymentMethodId The payment method id
     * @return string
     */
    public function getAdvocatePaymentMethod($strAccountSlug, $strAdvocateToken, $intAdvocatePaymentMethodId) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/payment-methods/' . $intAdvocatePaymentMethodId;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Update a payment method.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param integer $intAdvocatePaymentMethodId The payment method id
     * @param array $arrParams
     * Request Format All parameters in the content of the request are mandatory.
     * {
     *       "advocate_payment_method":{
     *           "username":"advocate_at_email.com",
     *           "description":"My main paypal account",
     *           "is_active": true
     *       }
     * }
     * @return string
     */
    public function putAdvocatePaymentMethod($strAccountSlug, $strAdvocateToken, $intAdvocatePaymentMethodId, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/payment-methods/' . $intAdvocatePaymentMethodId;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->put($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get the list of referrals.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param integer page.  The current page, default is 1.

     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: url, referral_origin_slug, created. Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getReferrals($strAccountSlug, $strAdvocateToken, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/referrals';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a referral.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param integer $intReferralId The referral id
     * @return string
     */
    public function getReferral($strAccountSlug, $strAdvocateToken, $intReferralId) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/referrals/' . $intReferralId;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Create a new Genius Referral referral.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @param array $arrParams
     * Request Format All parameters in the content of the request are mandaroty.
     * {
     *       "referral":{
     *           "referred_advocate_token":"10e33ae1b516873a8fe62880652759d558e27424",
     *           "referral_origin_slug": "facebook-share",
     *           "campaign_slug":"get-10-of-for-90-days",
     *           "http_referer":"http://www.google.com",
     *       }
     * }
     * @return string
     */
    public function postReferral($strAccountSlug, $strAdvocateToken, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/referrals';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->post($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get the list of bonuses.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer page.  The current page, default is 1.

     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: name, lastname, email, campaign_slug, from, to, created. Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: name, lastname, email, created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getBonuses($strAccountSlug, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Create a new bonus for a given advocate token. The system processes the advocate and creates a bonus for the
     * advocate's referrer if is needed. All restrictions set on the campaigns for this account will be check out before giving the bonus to the
     * advocate's referrer.
     *
     * @param string $strAccountSlug. The client account slug
     * @param array $arrParams
     * Request Format Not all parameters in the content of the request are mandatory.
     * Parameter payment_amount is optional.
     * 
     * {
     *       "bonus":{
     *           "advocate_token":"7c4ae87701ef6e6c9ab64941215da6b1f90f5c7a",
     *           "reference": "HSY7292D00",
     *           "payment_amount": 10
     *       }
     * }
     * @return string
     */
    public function postBonuses($strAccountSlug, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->post($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }
    
    /**
     * Force the system to give a bonus to an advocate. The system will not take into account the restriccions specified on the campaigns.
     *
     * Request Format
     * 
     * @param string $strAccountSlug. The client account slug
     * @param array $arrParams
     * 
     * Request Format
     * 
     *  {
     *      "bonus":{
     *          "advocate_token":"7c4ae87701ef6e6c9ab64941215da6b1f90f5c7a",
     *          "reference": "HSY7292D00",
     *          "bonus_amount": 10
     *      }
     *  }
     * @return string
     */
    public function postForceBonuses($strAccountSlug, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses/force';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->post($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get a bonus.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer $intBonusId The bonus id
     * @return string
     */
    public function getBonus($strAccountSlug, $intBonusId) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses/' . $intBonusId;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Check if there is a bonus to be given to the advocate. Allows the clients to check if there is a bonus to be given,
     * it simulates the behaivor of a POST request to /accounts/{account_slug}/bonuses resource. This resource is idempotent.
     *
     * @param string $strAccountSlug. The client account slug
     * @param array $arrParams
     * 
     * {
     *     "advocate_token":"7c4ae87701ef6e6c9ab64941215da6b1f90f5c7a",
     *     "reference": "HSY7292D00",
     *     "payment_amount": 10
     * }
     *
     * @return string
     */
    public function getBonusesCheckup($strAccountSlug, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses/checkup';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => $arrParams);

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get the list of bonuses traces.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer page.  The current page, default is 1.

     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: reference, result, bonus_amount, advocate_token, advocate_referrer_token, campaign_slug, from, to, created.
     *                           Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getBonusesTraces($strAccountSlug, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses/traces';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a bonus request trace.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer $intTraceId The trace id
     * @return string
     */
    public function getBonusesTrace($strAccountSlug, $intTraceId) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/bonuses/traces/' . $intTraceId;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get the list of campaings.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer page.  The current page, default is 1.

     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: name, description, start_date, end_date, is_active (true|false), created.
     *                           Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: campaign_slug, created, start_date, end_date, is_active. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getCampaigns($strAccountSlug, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/campaigns';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a campaign.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strCampaignSlug. The campaign slug
     * @return string
     */
    public function getCampaign($strAccountSlug, $strCampaignSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/campaigns/' . $strCampaignSlug;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get the list of redemption requests.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer page.  The current page, default is 1.

     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFilter. Allowed fields: redemption_request_id, name, lastname, email, request_status_slug, request_action_slug, from, to, created.
     *                           Use the following delimiters to build your
     *                           filters params. The vertical bar ('|') to separate individual filter
     *                           phrases and a double colon ('::') to separate the names and values.
     *                           The delimiter of the double colon (':') separates the property name
     *                           from the comparison value, enabling the comparison value to contain spaces.
     *                           Example: www.example.com\/users?filter='name::todd|city::denver|title::grand poobah'
     * @param string $strSort.   Allowed fields: name, lastname, email, created. Use sort query-string parameter that
     *                           contains a delimited set of property names. For each property name, sort
     *                           in ascending order, and for each property prefixed with a dash ('-') sort
     *                           in descending order. Separate each property name with a vertical bar ('|'),
     *                           which is consistent with the separation of the name\/value pairs in
     *                           filtering, above. For example, if we want to retrieve users in order of
     *                           their last name (ascending), first name (ascending) and hire date
     *                           (descending), the request might look like this
     *                           www.example.com\/users?sort='last_name|first_name|-hire_date'
     * @return string
     */
    public function getRedemptionRequests($strAccountSlug, $intPage = 1, $intLimit = 10, $strFilter = null, $strSort = null) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/redemption-requests';
        $arrHeaders = $this->getHeaders();
        $strFilter = array('query' => array('page' => $intPage, 'limit' => $intLimit, 'filter' => $strFilter, 'sort' => $strSort));

        $objRequest = $objWebClient->get($strUri, $arrHeaders, $strFilter);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Create a redemption request.
     *
     * @param string $strAccountSlug. The client account slug
     * @param array $arrParams
     * Request Format Mandatory parameters: advocate_token, request_status_slug, request_action_slug, currency_code, amount.
     * {
     *       "redemption_request":{
     *               "advocate_token":"k3ibasd723278v2b32389589ihjf",
     *               "request_status_slug":"requested",
     *               "request_action_slug": "credit",
     *               "currency_code":"USD",
     *               "amount":50,
     *               "description": "credit",
     *               "advocates_paypal_username":"alain_at_mail.com"
     *       }
     * }
     * @return string
     */
    public function postRedemptionRequest($strAccountSlug, array $arrParams) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/redemption-requests';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->post($strUri, $arrHeaders, $arrParams);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get a redemption request.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer $intRedemptionRequestId The redemption request id
     * @return string
     */
    public function getRedemptionRequest($strAccountSlug, $intRedemptionRequestId) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/redemption-requests/' . $intRedemptionRequestId;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Redeem a redemption request. After the redemption request is created it needs to be redeemed.
     * This will deduct the amount of the advocate unclaimed balance and move the request to the completed state.
     *
     * @param string $strAccountSlug. The client account slug
     * @param integer $intRedemptionRequestId. The redemption request id
     * @return string
     */
    public function patchRedemptionRequestRedemption($strAccountSlug, $intRedemptionRequestId) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/redemption-requests/' . $intRedemptionRequestId . '/redemption';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->patch($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get bonuses summary per referral origin.
     *
     * @param string $strAdvocateToken. The advocate token
     * @return string
     */
    public function getBonusesSummaryPerOriginReport($strAdvocateToken) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/bonuses-summary-per-origin';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array('advocate_token' => $strAdvocateToken)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get referrals summary per referral origin.
     *
     * @param string $strAdvocateToken. The advocate token
     * @return string
     */
    public function getReferralsSummaryPerOriginReport($strAdvocateToken) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/referrals-summary-per-origin';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array('advocate_token' => $strAdvocateToken)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Allow clients to test authentication on Genius Referrals platform.
     *
     * @return string
     */
    public function testAuthentication() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/test-authentication';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();

        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get bonuses redemption methods.
     *
     * @return string
     */
    public function getBonusesRedemptionMethods() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/bonuses-redemption-methods';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get bonuses redemption method.
     *
     * @param string $strBonusRedemptionMethodSlug The bonuses redemption method slug
     * @return string
     */
    public function getBonusRedemptionMethod($strBonusRedemptionMethodSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/bonuses-redemption-methods/' . $strBonusRedemptionMethodSlug;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get currencies.
     *
     * @return string
     */
    public function getCurrencies() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/currencies';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a currency.
     *
     * @param string $strCode The currency code
     * @return string
     */
    public function getCurrency($strCode) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/currencies/' . $strCode;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get redemption request actions.
     *
     * @return string
     */
    public function getRedemptionRequestsActions() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/redemption-request-actions';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a redemption request action.
     *
     * @param string $strRedemptionRequestActionSlug The redemption request action slug
     * @return string
     */
    public function getRedemptionRequestAction($strRedemptionRequestActionSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/redemption-request-actions/' . $strRedemptionRequestActionSlug;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get redemption request statuses.
     *
     * @return string
     */
    public function getRedemptionRequestStatuses() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/redemption-request-statuses';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a redemption request status.
     *
     * @param string $strRedemptionRequestStatusSlug. The redemption request status slug
     * @return string
     */
    public function getRedemptionRequestStatus($strRedemptionRequestStatusSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/redemption-request-statuses/' . $strRedemptionRequestStatusSlug;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get referral origins. This is needed when creating (POST) a new referral as referral_origin_slug refers to one of this origins.
     *
     * @return string
     */
    public function getReferralOrigins() {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/referral-origins';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get a referral origin by a given slug.
     *
     * @param string $strReferralOriginSlug. The referral origins slug
     * @return string
     */
    public function getReferralOrigin($strReferralOriginSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/utilities/referral-origins/' . $strReferralOriginSlug;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get list of share links.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @return string
     */
    public function getAdvocatesShareLinks($strAccountSlug, $strAdvocateToken) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken . '/share-links';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Delete advocates by account slug.
     *
     * This method deletes all the advocates in the account, so be careful.
     *
     * @param string $strAccountSlug. The client account slug
     * @return string
     */
    public function deleteAdvocates($strAccountSlug) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->delete($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Delete advocate by account slug and advocate token.     *
     *
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strAdvocateToken. The advocate token
     * @return string
     */
    public function deleteAdvocate($strAccountSlug, $strAdvocateToken) {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/accounts/' . $strAccountSlug . '/advocates/' . $strAdvocateToken;
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->delete($strUri, $arrHeaders);

        $this->objResponse = $objRequest->send();

        return $this->objResponse;
    }

    /**
     * Get bonuses daily given.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strCampaignSlug. The campaign slug
     * @param string $strAdvocateToken. The advocate token
     * @param string $strFrom.
     * @param string $strTo.
     *
     * @return string
     */
    public function getReportsBonusesDailyGiven($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '') {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/bonuses-daily-given';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array('client_account_slug' => $strAccountSlug,
                'campaign_slug' => $strCampaignSlug,
                'advocate_token' => $strAdvocateToken,
                'from' => $strFrom,
                'to' => $strTo)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get click daily participation.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strCampaignSlug. The campaign slug
     * @param string $strAdvocateToken. The advocate token
     * @param string $strFrom.
     * @param string $strTo.
     *
     * @return string
     */
    public function getReportsClickDailyParticipation($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '') {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/click-daily-participation';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array('client_account_slug' => $strAccountSlug,
                'campaign_slug' => $strCampaignSlug,
                'advocate_token' => $strAdvocateToken,
                'from' => $strFrom,
                'to' => $strTo)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get referral daily participation.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strCampaignSlug. The campaign slug
     * @param string $strAdvocateToken. The advocate token
     * @param string $strFrom.
     * @param string $strTo.
     *
     * @return string
     */
    public function getReportsReferralDailyParticipation($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '') {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/referral-daily-participation';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array('client_account_slug' => $strAccountSlug,
                'campaign_slug' => $strCampaignSlug,
                'advocate_token' => $strAdvocateToken,
                'from' => $strFrom,
                'to' => $strTo)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get share daily participation.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strCampaignSlug. The campaign slug
     * @param string $strAdvocateToken. The advocate token
     * @param string $strFrom.
     * @param string $strTo.
     *
     * @return string
     */
    public function getReportsShareDailyParticipation($strAccountSlug = '', $strCampaignSlug = '', $strAdvocateToken = '', $strFrom = '', $strTo = '') {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/share-daily-participation';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array('client_account_slug' => $strAccountSlug,
                'campaign_slug' => $strCampaignSlug,
                'advocate_token' => $strAdvocateToken,
                'from' => $strFrom,
                'to' => $strTo)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    /**
     * Get top advocates.
     *
     * @param string $strAccountSlug. The client account slug
     * @param string $strCampaignSlug. The campaign slug
     * @param integer $intLimit. Maximum number of results to return in the response.
     * @param string $strFrom.
     * @param string $strTo.
     *
     * @return string
     */
    public function getReportsTopAdvocates($strAccountSlug = '', $strCampaignSlug = '', $intLimit = 10, $strFrom = '', $strTo = '') {
        $objWebClient = $this->getWebClient();

        $strUri = $this->getApiUrl() . '/reports/top-advocates';
        $arrHeaders = $this->getHeaders();

        $objRequest = $objWebClient->get($strUri, $arrHeaders, array('query' => array(
                'account_slug' => $strAccountSlug,
                'campaign_slug' => $strCampaignSlug,
                'limit' => $intLimit,
                'from' => $strFrom,
                'to' => $strTo)));

        $this->objResponse = $objRequest->send();
        $strResponse = $this->objResponse->getBody(TRUE);

        return $strResponse;
    }

    private function getApiVersion() {
        if (file_exists(__DIR__ . '/config.php')) {
            require __DIR__ . '/config.php';
            return $apiConfig['api_version'];
        }
    }

    private function getHeaders() {
        $arrHeaders = array(
            'HTTP_ACCEPT' => 'application/json; version=' . $this->getApiVersion(),
            'CONTENT_TYPE' => 'application/json',
        );
        return $arrHeaders;
    }

}
