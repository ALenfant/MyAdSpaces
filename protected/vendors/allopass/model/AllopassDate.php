<?php
/**
 * @file AllopassDate.php
 * File of the class AllopassDate
 */

/**
 * Class providing object mapping of a date item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassDate
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
     * Method retrieving the date timestamp
     *
     * @return (integer) The partner timestamp
     */
    public function getTimestamp()
    {
        return (integer) $this->_xml->attributes()->timestamp;
    }
    
    /**
     * Method retrieving the date ISO-8601 representation
     *
     * @return (string) The date ISO-8601 representation
     */
    public function getDate()
    {
        return (string) $this->_xml->attributes()->date;
    }
}