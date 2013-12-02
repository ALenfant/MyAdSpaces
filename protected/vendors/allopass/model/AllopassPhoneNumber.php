<?php
/**
 * @file AllopassPhoneNumber.php
 * File of the class AllopassPhoneNumber
 */

/**
 * Class providing object mapping of a phone number item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassPhoneNumber
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
     * Method retrieving the phone number number
     *
     * @return (string) The phone number number
     */
    public function getValue()
    {
        return (string) $this->_xml->attributes()->value;
    }
    
    /**
     * Method retrieving the phone number description
     *
     * @return (string) The phone number description
     */
    public function getDescription()
    {
        return (string) $this->_xml->description;
    }
}