<?php
/**
 * @file AllopassPeriodicity.php
 * File of the class AllopassPeriodicity
 */

/**
 * Class providing object mapping of a Periodicity item
 *
 * @author Mathieu Robert <mrobert@hi-media.com>
 *
 * @date 2011 (c) Hi-media
 */
class AllopassPeriodicity
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
     * Method retrieving the periodicity value
     *
     * @return (integer) The periodicity value
     */
    public function getValue()
    {
        return (integer) $this->_xml->attributes()->value;
    }
    
    /**
     * Method retrieving the periodicity unit
     *
     * @return (string) The periodicity unit
     */
    public function getUnit()
    {
        return (string) $this->_xml->attributes()->unit;
    }
}