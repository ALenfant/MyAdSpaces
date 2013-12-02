<?php
/**
 * @file AllopassTransactionPrepareResponse.php
 * File of the class AllopassTransactionPrepareResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassDate.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';
require_once dirname(__FILE__) . '/AllopassPrice.php';
require_once dirname(__FILE__) . '/AllopassPricepoint.php';

/**
 * Class defining a transaction prepare request's response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassTransactionPrepareResponse extends AllopassApiMappingResponse
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
     * Method retrieving the transaction creation date
     *
     * @return (AllopassDate) The transaction creation date
     */
    public function getCreationDate()
    {
        return new AllopassDate($this->_xml->creation_date);
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
     * Method retrieving the transaction's pricepoint
     *
     * @return (AllopassPricepoint) The transaction's pricepoint
     */
    public function getPricepoint()
    {
        return new AllopassPricepoint($this->_xml->pricepoint);
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
     * Method retrieving the transaction buy url
     *
     * @return (string) The transaction buy url
     */
    public function getBuyUrl()
    {
        return (string) $this->_xml->buy_url;
    }
    
    /**
     * Method retrieving the transaction checkout button HTML string
     *
     * @return (string) The transaction checkout button HTML string
     */
    public function getCheckoutButton()
    {
        return (string) $this->_xml->checkout_button;
    }
}