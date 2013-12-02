<?php
/**
 * @file AllopassWebsite.php
 * File of the class AllopassWebsite
 */

require_once dirname(__FILE__) . '/AllopassProduct.php';

/**
 * Class providing object mapping of a website item
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassWebsite
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
     * Method retrieving the website id
     *
     * @return (integer) The website id
     */
    public function getId()
    {
        return (integer) $this->_xml->attributes()->id;
    }
    
    /**
     * Method retrieving the website name
     *
     * @return (string) The website name
     */
    public function getName()
    {
        return (string) $this->_xml->attributes()->name;
    }
    
    /**
     * Method retrieving the website url
     *
     * @return (string) The website url
     */
    public function getUrl()
    {
        return (string) $this->_xml->attributes()->url;
    }
    
    /**
     * Method retrieving if the website's audience is restricted
     *
     * @return (boolean) If the website's audience is restricted
     */
    public function isAudienceRestricted()
    {
        return ($this->_xml->attributes()->audience_restricted == 'true');
    }
    
    /**
     * Method retrieving the website category
     *
     * @return (string) The website category
     */
    public function getCategory()
    {
        return (string) $this->_xml->attributes()->category;
    }
    
    /**
     * Method creating the product array 
     * @return multitype:AllopassProduct
     */
    public function getProducts()
    {
        $products = array();
        
        if (isset($this->_xml->product)) {
            foreach($this->_xml->product as $productXml) {
                $products[] = new AllopassProduct($productXml);
            }
        }
        
        return $products;
    }
}