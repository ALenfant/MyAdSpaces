<?php
/**
 * @file AllopassProductDetailResponse.php
 * File of the class AllopassProductDetailResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassDate.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';

/**
 * Class defining a product detail request's response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassProductDetailResponse extends AllopassApiMappingResponse
{
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
     * Method retrieving the product id
     *
     * @return (integer) The product id
     */
    public function getId()
    {
        return (integer) $this->_xml->id;
    }
    
    /**
     * Method retrieving the product key
     *
     * @return (string) The product key
     */
    public function getKey()
    {
        return (string) $this->_xml->key;
    }
    
    /**
     * Method retrieving the product access-type
     *
     * @return (string) The product access-type
     */
    public function getAccessType()
    {
        return (string) $this->_xml->access_type;
    }
    
    /**
     * Method retrieving the product creation date
     *
     * @return (AllopassDate) The product creation date
     */
    public function getCreationDate()
    {
        return new AllopassDate($this->_xml->creation_date);
    }
    
    /**
     * Method retrieving the product name
     *
     * @return (string) The product name
     */
    public function getName()
    {
        return (string) $this->_xml->name;
    }
    
    /**
     * Method retrieving the product's website
     *
     * @return (AllopassWebsite) The product's website
     */
    public function getWebsite()
    {
        return new AllopassWebsite($this->_xml->website);
    }
    
    /**
     * Method retrieving the product expected number of codes
     *
     * @return (integer) The product expected number of codes
     */
    public function getExpectedNumberOfCodes()
    {
        return (integer) $this->_xml->expected_number_of_codes;
    }
    
    /**
     * Method retrieving the product purchase url
     *
     * @return (string) The product purchase url
     */
    public function getPurchaseUrl()
    {
        return (string) $this->_xml->purchase_url;
    }
    
    /**
     * Method retrieving the product forward url
     *
     * @return (string) The product forward url
     */
    public function getForwardUrl()
    {
        return (string) $this->_xml->forward_url;
    }
    
    /**
     * Method retrieving the product error url
     *
     * @return (string) The product error url
     */
    public function getErrorUrl()
    {
        return (string) $this->_xml->error_url;
    }
    
    /**
     * Method retrieving the product notification url
     *
     * @return (string) The product notification url
     */
    public function getNotificationUrl()
    {
        return (string) $this->_xml->notification_url;
    }
}