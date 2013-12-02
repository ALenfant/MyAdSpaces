<?php
/**
 * @file AllopassPayout.php
 * File of the class AllopassPayout
 */

/**
 * Class providing object mapping of a payout item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassPayout
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
     * Method retrieving the payout currency
     *
     * @return (string) The payout currency
     */
    public function getCurrency()
    {
        return (string) $this->_xml->attributes()->currency;
    }
    
    /**
     * Method retrieving the payout amount
     *
     * @return (float) The payout amount
     */
    public function getAmount()
    {
        return (float) $this->_xml->attributes()->amount;
    }
    
    /**
     * Method retrieving the payout exchange rate
     *
     * @return (double) The payout exchange rate
     */
    public function getExchange()
    {
        return (double) $this->_xml->attributes()->exchange;
    }
    
    /**
     * Method retrieving the payout reference currency
     *
     * @return (string) The payout reference currency
     */
    public function getReferenceCurrency()
    {
        return (string) $this->_xml->attributes()->reference_currency;
    }
    
    /**
     * Method retrieving the payout reference amount
     *
     * @return (float) The payout reference amount
     */
    public function getReferenceAmount()
    {
        return (float) $this->_xml->attributes()->reference_amount;
    }
}