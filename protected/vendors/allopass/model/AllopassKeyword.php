<?php
/**
 * @file AllopassKeyword.php
 * File of the class AllopassKeyword
 */

/**
 * Class providing object mapping of a keyword item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassKeyword
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
     * Method retrieving the keyword name
     *
     * @return (string) The keyword name
     */
    public function getName()
    {
        return (string) $this->_xml->attributes()->name;
    }
    
    /**
     * Method retrieving the keyword shortcode
     *
     * @return (string) The keyword shortcode
     */
    public function getShortcode()
    {
        return (string) $this->_xml->attributes()->shortcode;
    }
    
    /**
     * Method retrieving the keyword operators
     *
     * @return (string) The keyword operators
     */
    public function getOperators()
    {
        return (string) $this->_xml->attributes()->operators;
    }
    
    /**
     * Method retrieving the keyword number billed messages
     *
     * @return (integer) The keyword number billed messages
     */
    public function getNumberBilledMessages()
    {
        return (integer) $this->_xml->attributes()->number_billed_messages;
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