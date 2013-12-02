<?php
/**
 * @file AllopassPrice.php
 * File of the class AllopassPrice
 */

/**
 * Class providing object mapping of a price item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassPrice
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
     * Method retrieving the price currency
     *
     * @return (string) The price currency
     */
    public function getCurrency()
    {
        return (string) $this->_xml->attributes()->currency;
    }
    
    /**
     * Method retrieving the price amount
     *
     * @return (float) The price amount
     */
    public function getAmount()
    {
        return (float) $this->_xml->attributes()->amount;
    }
    
    /**
     * Method retrieving the price exchange rate
     *
     * @return (double) The price exchange rate
     */
    public function getExchange()
    {
        return (double) $this->_xml->attributes()->exchange;
    }
    
    /**
     * Method retrieving the price reference currency
     *
     * @return (string) The price reference currency
     */
    public function getReferenceCurrency()
    {
        return (string) $this->_xml->attributes()->reference_currency;
    }
    
    /**
     * Method retrieving the price reference amount
     *
     * @return (float) The price reference amount
     */
    public function getReferenceAmount()
    {
        return (float) $this->_xml->attributes()->reference_amount;
    }
}