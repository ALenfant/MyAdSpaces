<?php
/**
 * @file AllopassRegion.php
 * File of the class AllopassRegion
 */

require_once dirname(__FILE__) . '/AllopassCountry.php';

/**
 * Class providing object mapping of a region item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassRegion
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
     * Method retrieving the region name
     *
     * @return (string) The region name
     */
    public function getName()
    {
        return (string) $this->_xml->attributes()->name;
    }
    
    /**
     * Method retrieving the region's countries
     *
     * @return (AllopassCountry[]) The region's countries
     */
    public function getCountries()
    {
        $countries = array();
        
        foreach ($this->_xml->children() as $child) {
            $countries[] = new AllopassCountry($child);
        }
        
        return $countries;
    }
}