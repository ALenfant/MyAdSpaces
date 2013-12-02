<?php
/**
 * @file AllopassOnetimeValidateCodesResponse.php
 * File of the class AllopassOnetimeValidateCodesResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassDate.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';
require_once dirname(__FILE__) . '/AllopassPrice.php';
require_once dirname(__FILE__) . '/AllopassCode.php';
require_once dirname(__FILE__) . '/AllopassPartner.php';

/**
 * Class defining a onetime validate-codes request's response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2010 (c) Hi-media
 */
class AllopassOnetimeValidateCodesResponse extends AllopassApiMappingResponse
{
    /**
     * The validation is successful
     */
    const VALIDATECODES_SUCCESS = 0;
    
    /**
     * The validation failed
     */
    const VALIDATECODES_FAILED  = 1;
    
    
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
     * Method retrieving the validation status
     *
     * @return (integer) The validation status
     */
    public function getStatus()
    {
        return (integer) $this->_xml->status;
    }
    
    /**
     * Method retrieving the validation status description
     *
     * @return (string) The validation status description
     */
    public function getStatusDescription()
    {
        return (string) $this->_xml->status_description;
    }
    
    /**
     * Method retrieving the access-type
     *
     * @return (string) The access-type
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
     * Method retrieving the validation's price
     *
     * @return (AllopassPrice) The validation's price
     */
    public function getPrice()
    {
        return new AllopassPrice($this->_xml->price);
    }
    
    /**
     * Method retrieving the validation's paid price
     *
     * @return (AllopassPrice) The validation's paid price
     */
    public function getPaid()
    {
        return new AllopassPrice($this->_xml->paid);
    }
    
    /**
     * Method retrieving the validation date
     *
     * @return (AllopassDate) The validation date
     */
    public function getValidationDate()
    {
        return new AllopassDate($this->_xml->validation_date);
    }
    
    /**
     * Method retrieving the product name
     *
     * @return (string) The product name
     */
    public function getProductName()
    {
         return (string) $this->_xml->product_name;
    }
    
    /**
     * Method retrieving the website
     *
     * @return (AllopassWebsite) The website
     */
    public function getWebsite()
    {
        return new AllopassWebsite($this->_xml->website);
    }
    
    /**
     * Method retrieving the customer ip
     *
     * @return (string) The customer ip
     */
    public function getCustomerIp()
    {
         return (string) $this->_xml->customer_ip;
    }
    
    /**
     * Method retrieving the customer country
     *
     * @return (string) The customer country
     */
    public function getCustomerCountry()
    {
         return (string) $this->_xml->customer_country;
    }
    
    /**
     * Method retrieving the expected number of codes
     *
     * @return (integer) The expected number of codes
     */
    public function getExpectedNumberOfCodes()
    {
         return (integer) $this->_xml->expected_number_of_codes;
    }
    
    /**
     * Method retrieving the validation codes
     *
     * @return (AllopassCode[]) The validation codes
     */
    public function getCodes()
    {
        $codes = array();
        
        foreach ($this->_xml->codes->children() as $child) {
            $codes[] = new AllopassCode($child);
        }
        
        return $codes;
    }
    
    /**
     * Method retrieving the merchant transaction id
     *
     * @return (string) The merchant transaction id
     */
    public function getMerchantTransactionId()
    {
         return (string) $this->_xml->merchant_transaction_id;
    }
    
    /**
     * Method retrieving the client data
     *
     * @return (string) The client data
     */
    public function getData()
    {
         return (string) $this->_xml->data;
    }
    
    /**
     * Method retrieving the affiliate code
     *
     * @return (string) The affiliate code
     */
    public function getAffiliate()
    {
         return (string) $this->_xml->affiliate;
    }
    
    /**
     * Method retrieving the partners
     *
     * @return (AllopassPartner[]) The partners
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