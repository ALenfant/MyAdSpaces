<?php
/**
 * @file AllopassTransactionDetailResponse.php
 * File of the class AllopassTransactionDetailResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassDate.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';
require_once dirname(__FILE__) . '/AllopassPrice.php';
require_once dirname(__FILE__) . '/AllopassPartner.php';

/**
 * Class defining a transaction detail request's response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassTransactionDetailResponse extends AllopassApiMappingResponse
{
    /**
     * The transaction is at first step : initialization
     */
    const TRANSACTION_INIT               = -1;
    
    /**
     * The transaction is successful
     */
    const TRANSACTION_SUCCESS            = 0;
    
    /**
     * The transaction failed due to insufficient funds
     */
    const TRANSACTION_INSUFFICIENT_FUNDS = 1;
    
    /**
     * The transaction timeouted
     */
    const TRANSACTION_TIMEOUT            = 2;
    
    /**
     * The transaction has been cancelled by user
     */
    const TRANSACTION_CANCELLED          = 3;
    
    /**
     * The transaction has been blocked due to fraud suspicions
     */
    const TRANSACTION_ANTI_FRAUD         = 4;
    
    /**
     * Constructor
     * 
     * @param signature (string) Expected response signature
     * @param headers (string) HTTP headers of the response
     * @param body (string) Raw data of the response
     */
    public function __construct($signature, $headers, $body)
    {
        parent::__construct($signature, $headers, $body);
    }
    
    /**
     * Method retrieving the transaction status
     *
     * @return (integer) The transaction status
     */
    public function getStatus()
    {
        return (integer) $this->_xml->status;
    }
    
    /**
     * Method retrieving the transaction status description
     *
     * @return (string) The transaction status description
     */
    public function getStatusDescription()
    {
        return (string) $this->_xml->status_description;
    }
    
    /**
     * Method retrieving the transaction access-type
     *
     * @return (string) The transaction access-type
     */
    public function getAccessType()
    {
        return (string) $this->_xml->access_type;
    }
    
    /**
     * Method retrieving the transaction id
     *
     * @return (string) The transaction id
     */
    public function getTransactionId()
    {
        return (string) $this->_xml->transaction_id;
    }
    
    /**
     * Method retrieving the transaction's price
     *
     * @return (AllopassPrice) The transaction's price
     */
    public function getPrice()
    {
        return new AllopassPrice($this->_xml->price);
    }
    
    /**
     * Method retrieving the transaction's paid price
     *
     * @return (AllopassPrice) The transaction's paid price
     */
    public function getPaid()
    {
        return new AllopassPrice($this->_xml->paid);
    }
    
    /**
     * Method retrieving the transaction creation date
     *
     * @return (AllopassDate) The transaction creation date
     */
    public function getCreationDate()
    {
        return new AllopassDate($this->_xml->creation_date);
    }
    
    /**
     * Method retrieving the transaction end date
     *
     * @return (AllopassDate) The transaction end date
     */
    public function getEndDate()
    {
         return new AllopassDate($this->_xml->end_date);
    }
    
    /**
     * Method retrieving the transaction product name
     *
     * @return (string) The transaction product name
     */
    public function getProductName()
    {
         return (string) $this->_xml->product_name;
    }
    
    /**
     * Method retrieving the transaction's website
     *
     * @return (AllopassWebsite) The transaction's website
     */
    public function getWebsite()
    {
        return new AllopassWebsite($this->_xml->website);
    }
    
    /**
     * Method retrieving the transaction customer ip
     *
     * @return (string) The transaction customer ip
     */
    public function getCustomerIp()
    {
         return (string) $this->_xml->customer_ip;
    }
    
    /**
     * Method retrieving the transaction customer country
     *
     * @return (string) The transaction customer country
     */
    public function getCustomerCountry()
    {
         return (string) $this->_xml->customer_country;
    }
    
    /**
     * Method retrieving the transaction expected number of codes
     *
     * @return (integer) The transaction expected number of codes
     */
    public function getExpectedNumberOfCodes()
    {
         return (integer) $this->_xml->expected_number_of_codes;
    }
    
    /**
     * Method retrieving if the transaction is in direct access
     *
     * @return (boolean) If the transaction is in direct access
     */
    public function isDirectAccess()
    {
        return ($this->_xml->direct_access == 'true');
    }
    
    /**
     * Method retrieving the transaction codes
     *
     * @return (string[]) The transaction codes
     */
    public function getCodes()
    {
        $codes = array();
        
        foreach ($this->_xml->codes->children() as $child) {
            $codes[] = (string) $child;
        }
        
        return $codes;
    }
    
    /**
     * Method retrieving the transaction merchant transaction id
     *
     * @return (string) The transaction merchant transaction id
     */
    public function getMerchantTransactionId()
    {
         return (string) $this->_xml->merchant_transaction_id;
    }
    
    /**
     * Method retrieving the transaction client data
     *
     * @return (string) The transaction client data
     */
    public function getData()
    {
         return (string) $this->_xml->data;
    }
    
    /**
     * Method retrieving the transaction affiliate code
     *
     * @return (string) The transaction affiliate code
     */
    public function getAffiliate()
    {
         return (string) $this->_xml->affiliate;
    }
    
    /**
     * Method retrieving the transaction partners
     *
     * @return (AllopassPartner[]) The transaction partners
     */
    public function getPartners()
    {
        $partners = array();
        
        foreach ($this->_xml->partners->children() as $child) {
            $partners[] = new AllopassPartner($child);
        }
        
        return $partners;
    }
}