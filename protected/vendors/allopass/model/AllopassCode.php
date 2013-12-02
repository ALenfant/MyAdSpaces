<?php
/**
 * @file AllopassCode.php
 * File of the class AllopassCode
 */

require_once dirname(__FILE__) . '/AllopassPricepoint.php';
require_once dirname(__FILE__) . '/AllopassPrice.php';

/**
 * Class providing object mapping of a code item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2010 (c) Hi-media
 */
class AllopassCode
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
     * Method retrieving the code value (the code string)
     *
     * @return (string) The code value
     */
    public function getValue()
    {
        return (string) $this->_xml->value;
    }
    
    /**
     * Method retrieving the code pricepoint
     *
     * @return (AllopassPricepoint) The code pricepoint
     */
    public function getPricepoint()
    {
        return new AllopassPricepoint($this->_xml->pricepoint);
    }
    
    /**
     * Method retrieving the code price
     *
     * @return (AllopassPrice) The code price
     */
    public function getPrice()
    {
        return new AllopassPrice($this->_xml->price);
    }
    
    /**
     * Method retrieving the code paid price
     *
     * @return (AllopassPrice) The code paid price
     */
    public function getPaid()
    {
        return new AllopassPrice($this->_xml->paid);
    }
    
    /**
     * Method retrieving the code payout
     *
     * @return (AllopassPrice) The code payout
     */
    public function getPayout()
    {
        return new AllopassPrice($this->_xml->payout);
    }
}