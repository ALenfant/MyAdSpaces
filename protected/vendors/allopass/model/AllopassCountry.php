<?php
/**
 * @file AllopassCountry.php
 * File of the class AllopassCountry
 */

/**
 * Class providing object mapping of a country item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassCountry
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
     * Method retrieving the country code
     *
     * @return (string) The country code
     */
    public function getCode()
    {
        return (string) $this->_xml->attributes()->code;
    }
    
    /**
     * Method retrieving the country name
     *
     * @return (string) The country name
     */
    public function getName()
    {
        return (string) $this->_xml->attributes()->name;
    }
}