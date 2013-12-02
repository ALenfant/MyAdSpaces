<?php
/**
 * @file AllopassMarket.php
 * File of the class AllopassMarket
 */

require_once dirname(__FILE__) . '/AllopassPricepoint.php';

/**
 * Class providing object mapping of a market item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassMarket
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
     * Method retrieving the market country code
     *
     * @return (string) The market country code
     */
    public function getCountryCode()
    {
        return (string) $this->_xml->attributes()->country_code;
    }
    
    /**
     * Method retrieving the  market country
     *
     * @return (string) The market country
     */
    public function getCountry()
    {
        return (string) $this->_xml->attributes()->country;
    }
    
    /**
     * Method retrieving the market pricepoints
     *
     * @return (AllopassPricepoint[]) The market pricepoints
     */
    public function getPricepoints()
    {
        $pricepoints = array();
        
        foreach ($this->_xml->children() as $child) {
             $pricepoints[] = new AllopassPricepoint($child);
        }
        
        return $pricepoints;
    }
}