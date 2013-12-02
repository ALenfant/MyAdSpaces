<?php
/**
 * @file AllopassProduct.php
 * File of the class AllopassProduct
 */

/**
 * Class providing object mapping of a product item
 *
 * @author Mathieu Robert <mrobert@hi-media.com>
 *
 * @date 2011 (c) Hi-media
 */
class AllopassProduct
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
     * Method retrieving the product id
     *
     * @return (integer) The product id
     */
    public function getId()
    {
        return (integer) $this->_xml->attributes()->id;
    }
    
    /**
     * Method retrieving the product name
     *
     * @return (string) The product name
     */
    public function getName()
    {
        return (string) $this->_xml->attributes()->name;
    }
}