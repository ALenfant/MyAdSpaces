<?php
/**
 * @file AllopassOffer.php
 * File of the class AllopassOffer
 */

/**
 * Class providing object mapping of a offer item
 *
 * @author Mathieu Robert <mrobert@hi-media.com>
 *
 * @date 2011 (c) Hi-media
 */
class AllopassOffer
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
     * Method retrieving the offer id
     *
     * @return (integer) The offer id
     */
    public function getId()
    {
        return (integer) $this->_xml->attributes()->id;
    }
    
    /**
     * Method retrieving the offer name
     *
     * @return (string) The offer name
     */
    public function getName()
    {
        return (string) $this->_xml->attributes()->name;
    }
}