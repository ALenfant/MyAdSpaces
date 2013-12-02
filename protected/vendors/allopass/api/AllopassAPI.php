<?php
/**
 * @file AllopassAPI.php
 * File of the class AllopassAPI
 */

require_once dirname(__FILE__) . '/../model/AllopassOnetimePricingRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassOnetimeDiscretePricingRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassOnetimeValidateCodesRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassOnetimeButtonRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassOnetimeDiscreteButtonRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassProductDetailRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassTransactionPrepareRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassTransactionDetailRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassTransactionMerchantRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassSubscriptionDetailRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassSubscriptionListRequest.php';
require_once dirname(__FILE__) . '/../model/AllopassWebsiteRequest.php';

/**
 * Class providing a convenient way to make Allopass API requests
 *
 * @author Mathieu Robert <mrobert@hi-media.com>
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2011 (c) Hi-media
 */
class AllopassAPI
{
    /**
     * Email of the configurated account
     */
    private $_configurationEmailAccount;
    
    
    /**
     * Constructor
     *
     * @param configurationEmailAccount (string) Email of the configurated account
     * If email is null, the first account is considered
     */
    public function __construct($configurationEmailAccount = null)
    {
        $this->_configurationEmailAccount = $configurationEmailAccount;
    }
    
    /**
     * Method for changing the configuration account email
     *
     * @param configurationEmailAccount (string) Email of the configurated account
     * If email is null, the first account is considered
     *
     * @return (AllopassAPI) The class instance
     */
    public function setConfigurationEmailAccount($configurationEmailAccount) {
        $this->_configurationEmailAccount = $configurationEmailAccount;
        
        return $this;
    }
    
    /**
     * Method performing a onetime pricing request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassOnetimePricingResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getOnetimePricing(array('site_id' => 127042, 'country' => 'FR'));
     * echo $response->getWebsite()->getName(), "\n-----\n";
     * foreach ($response->getCountries() as $country) {
     *   echo $country->getCode(), "\n-----\n";
     *   echo $country->getName(), "\n-----\n";
     * }
     * @endcode
     */
    public function getOnetimePricing(array $parameters, $mapping = true) {
        $request = new AllopassOnetimePricingRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a onetime discrete pricing request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassOnetimePricingResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getOnetimeDiscretePricing(array('site_id' => 127042, 'country' => 'FR', 'amount' => 12));
     * echo $response->getWebsite()->getName(), "\n-----\n";
     * foreach ($response->getCountries() as $country) {
     *   echo $country->getCode(), "\n-----\n";
     *   echo $country->getName(), "\n-----\n";
     * }
     * @endcode
     */
    public function getOnetimeDiscretePricing(array $parameters, $mapping = true) {
        $request = new AllopassOnetimeDiscretePricingRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a onetime validate codes request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassOnetimeValidateCodesResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->validateCodes(array('site_id' => 127042, 'product_id' => 354926, 'code' => array('9M7QU457')));
     * echo $response->getStatus(), "\n-----\n";
     * echo $response->getStatusDescription(), "\n-----\n";
     * @endcode
     */
    public function validateCodes(array $parameters, $mapping = true) {
        $request = new AllopassOnetimeValidateCodesRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a onetime button request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassOnetimeButtonResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->createButton(array('site_id' => 127042, 'product_name' => 'premium sms access', 'forward_url'=> 'http://product-page.com', 'amount' => 3, 'reference_currency' => 'EUR', 'price_mode' => 'price', 'price_policy' => 'high-only'));
     * echo $response->getButtonId(), "\n-----\n";
     * echo $response->getBuyUrl(), "\n-----\n";
     * @endcode
     */
    public function createButton(array $parameters, $mapping = true) {
        $request = new AllopassOnetimeButtonRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a onetime discrete button request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassOnetimeButtonResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->createDiscreteButton(array('site_id' => 127042, 'product_name' => 'discrete premium sms access', 'forward_url'=> 'http://product-page.com', 'amount' => 3, 'reference_currency' => 'EUR', 'price_mode' => 'price', 'price_policy' => 'high-only'));
     * echo $response->getButtonId(), "\n-----\n";
     * echo $response->getBuyUrl(), "\n-----\n";
     * @endcode
     */
    public function createDiscreteButton(array $parameters, $mapping = true) {
        $request = new AllopassOnetimeDiscreteButtonRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a product detail request
     *
     * @param id (integer) The product id
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassProductDetailResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getProduct(354926);
     * echo $response->getName(), "\n-----\n";
     * @endcode
     */
    public function getProduct($id, array $parameters = array(), $mapping = true) {
        $request = new AllopassProductDetailRequest(array('id' => $id) + $parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a transaction prepare request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassTransactionPrepareResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->prepareTransaction(array('site_id' => 127042, 'pricepoint_id' => 2, 'product_name' => 'premium calling product', 'forward_url' => 'http://product-page.com'));
     * echo $response->getBuyUrl(), "\n-----\n";
     * echo $response->getCheckoutButton(), "\n-----\n";
     * @endcode
     */
    public function prepareTransaction(array $parameters, $mapping = true) {
        $request = new AllopassTransactionPrepareRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a transaction detail request based on the transaction id
     *
     * @param id (string) The transaction id
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassTransactionDetailResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getTransaction('3f5506ac-5345-45e4-babb-96570aafdf6a');
     * echo $response->getPaid()->getCurrency(), "\n-----\n";
     * echo $response->getPaid()->getAmount(), "\n-----\n";
     * @endcode
     */
    public function getTransaction($id, array $parameters = array(), $mapping = true) {
        $request = new AllopassTransactionDetailRequest(array('id' => $id) + $parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
    
    /**
     * Method performing a transaction detail request based on the merchant transaction id
     *
     * @param id (string) The merchant transaction id
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassTransactionDetailResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getTransactionMerchant('TRX20091112134569B8');
     * echo $response->getPaid()->getCurrency(), "\n-----\n";
     * echo $response->getPaid()->getAmount(), "\n-----\n";
     * @endcode
     */
    public function getTransactionMerchant($id, array $parameters = array(), $mapping = true) {
        $request = new AllopassTransactionMerchantRequest(array('id' => $id) + $parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }

    /**
     * Method performing a subscription detail request based on the subscriber_reference
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassSubscriptionDetailResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getSubscription('Z556W642');
     * echo $response->getStatus(), "\n-----\n";
     * echo $response->getStatusDescription(), "\n-----\n";
     * echo $response->getAccessType(), "\n-----\n";
     * echo $response->getSubscriberReference(), "\n-----\n";
     * @endcode
     */    
    public function getSubscription($subscriberReference, array $parameters = array(), $mapping = true) {
        $request = new AllopassSubscriptionDetailRequest(array('subscriber_reference' => $subscriberReference) + $parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }

    /**
     * Method performing a subscription list request based on mixed params
     *
     * @param siteId (Integer) current site identifier to used
     * @param productId (Integer) current product identifier to used
     * @param paymentModeId (Integer) current paymentMode value
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassSubscriptionListResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getSubscriptionList(127042, 123456, 4);
     * echo $response->getSubscriberReference(), "\n-----\n";
     * @endcode
     */   
    public function getSubscriptionList($siteId, $productId, $paymentModeId, array $parameters = array(), $mapping = true) {
        $request = new AllopassSubscriptionListRequest(array('site_id' => $siteId, 'product_id' => $productId, 'payment_mode_id' => $paymentModeId) + $parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }   
 
    /**
     * Method performing a website request
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     *
     * @return (AllopassApiResponse) The API call response
     * Will be a AllopassSWebsiteResponse instance if mapping is true, an AllopassApiPlainResponse if not
     *
     * @code
     * require_once 'apikit/php5/api/AllopassAPI.php';
     * $api = new AllopassAPI();
     * $response = $api->getWebsite();
     * foreach($response->getWebsites() as $website) {
     *    echo "Website {$website->getId()}\n";
     *    foreach($website->getProducts() as $product) {
     *       echo "Product {$product->getId()}\n";
     *    }
     * }
     * @endcode
     */
    public function getWebsite(array $parameters = array(), $mapping = true) {
        $request = new AllopassWebsiteRequest($parameters, $mapping, $this->_configurationEmailAccount);
        
        return $request->call();
    }
}
