<?php
/**
 * @file AllopassOnetimeButtonResponse.php
 * File of the class AllopassOnetimeButtonResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassDate.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';

/**
 * Class defining a onetime button request's response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2010 (c) Hi-media
 */
class AllopassOnetimeButtonResponse extends AllopassApiMappingResponse
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
     * Method retrieving the button access-type
     *
     * @return (string) The button access-type
     */
    public function getAccessType()
    {
        return (string) $this->_xml->access_type;
    }
    
    /**
     * Method retrieving the button id
     *
     * @return (string) The button id
     */
    public function getButtonId()
    {
        return (string) $this->_xml->button_id;
    }
    
    /**
     * Method retrieving the button creation date
     *
     * @return (AllopassDate) The button creation date
     */
    public function getCreationDate()
    {
        return new AllopassDate($this->_xml->creation_date);
    }
    
    /**
     * Method retrieving the button's website
     *
     * @return (AllopassWebsite) The button's website
     */
    public function getWebsite()
    {
        return new AllopassWebsite($this->_xml->website);
    }
    
    /**
     * Method retrieving the button buy url
     *
     * @return (string) The button buy url
     */
    public function getBuyUrl()
    {
        return (string) $this->_xml->buy_url;
    }
    
    /**
     * Method retrieving the button checkout HTML string
     *
     * @return (string) The button checkout HTML string
     */
    public function getCheckoutButton()
    {
        return (string) $this->_xml->checkout_button;
    }
}