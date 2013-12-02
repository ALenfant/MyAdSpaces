<?php
/**
 * @file AllopassPricepoint.php
 * File of the class Pricepoint
 */

require_once dirname(__FILE__) . '/AllopassPrice.php';
require_once dirname(__FILE__) . '/AllopassPayout.php';
require_once dirname(__FILE__) . '/AllopassPhoneNumber.php';
require_once dirname(__FILE__) . '/AllopassKeyword.php';

/**
 * Class providing object mapping of a pricepoint item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassPricepoint
{
    /**
     * (SimpleXMLElement) SimpleXML object representation of the item
     */
    private $_xml;
    
    
    /**
     * Constructor
     *
     * @param xml (SimpleXMLElement) The SimpleXML object representation of the item
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
    }
    
    /**
     * Method retrieving the pricepoint id
     *
     * @return (integer) The pricepoint id
     */
    public function getId()
    {
        return (integer) $this->_xml->attributes()->id;
    }
    
    /**
     * Method retrieving the pricepoint type
     *
     * @return (string) The pricepoint type
     */
    public function getType()
    {
        return (string) $this->_xml->attributes()->type;
    }
    
    /**
     * Method retrieving the pricepoint category
     *
     * @return (string) The pricepoint category
     */
    public function getCategory()
    {
        return (string) $this->_xml->attributes()->category;
    }
    
    /**
     * Method retrieving the pricepoint country code
     *
     * @return (string) The pricepoint country code
     */
    public function getCountryCode()
    {
        return (string) $this->_xml->attributes()->country_code;
    }
    
    /**
     * Method retrieving the pricepoint's price
     *
     * @return (AllopassPrice) The pricepoint's price
     */
    public function getPrice()
    {
        $object = $this->_xml->price;
        
        return (empty($object)) ? null : new AllopassPrice($object);
    }
    
    /**
     * Method retrieving the pricepoint's payout
     *
     * @return (AllopassPrice) The pricepoint's payout
     */
    public function getPayout()
    {
        $object = $this->_xml->payout;
        
        return (empty($object)) ? null : new AllopassPayout($object);
    }
    
    /**
     * Method retrieving the pricepoint buy url
     *
     * @return (string) The pricepoint buy url
     */
    public function getBuyUrl()
    {
        return (string) $this->_xml->buy_url;
    }
    
    /**
     * Method retrieving the pricepoint's phone numbers
     *
     * @return (AllopassPhoneNumber[]) The pricepoint's phone numbers
     */
    public function getPhoneNumbers()
    {
        $phoneNumbers = array();
        
        $object = $this->_xml->phone_numbers;
        
        if (empty($object)) {
            return $phoneNumbers;
        }
        
        foreach ($object->children() as $child) {
            $phoneNumbers[] = new AllopassPhoneNumber($child);
        }
        
        return $phoneNumbers;
    }
    
    /**
     * Method retrieving the pricepoint's keywords
     *
     * @return (AllopassKeyword[]) The pricepoint's keywords
     */
    public function getKeywords()
    {
        $keywords = array();
        
        $object = $this->_xml->keywords;
        
        if (empty($object)) {
            return $keywords;
        }
        
        foreach ($object->children() as $child) {
            $keywords[] = new AllopassKeyword($child);
        }
        
        return $keywords;
    }
    
    /**
     * Method retrieving the pricepoint description
     *
     * @return (string) The pricepoint description
     */
    public function getDescription()
    {
        return (string) $this->_xml->description;
    }
}