<?php
/**
 * @file AllopassOnetimePricingResponse.php
 * File of the class AllopassOnetimePricingResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassDate.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';
require_once dirname(__FILE__) . '/AllopassCountry.php';
require_once dirname(__FILE__) . '/AllopassRegion.php';
require_once dirname(__FILE__) . '/AllopassMarket.php';

/**
 * Class defining a onetime pricing/discrete-pricing request's response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassOnetimePricingResponse extends AllopassApiMappingResponse
{
    public function __construct($signature, $headers, $body)
    {
        parent::__construct($signature, $headers, $body);
    }
    
    /**
     * Method retrieving the creation date
     *
     * @return (AllopassDate) The creation date
     */
    public function getCreationDate()
    {
        return new AllopassDate($this->_xml->creation_date);
    }
    
    /**
     * Method retrieving the customer ip
     *
     * @return (string) The customer ip
     */
    public function getCustomerIp()
    {
        return (string) $this->_xml->customer_ip;
    }
    
    /**
     * Method retrieving the customer country
     *
     * @return (string) The customer country
     */
    public function getCustomerCountry()
    {
        return (string) $this->_xml->customer_country;
    }
    
    /**
     * Method retrieving the website
     *
     * @return (AllopassWebsite) The website
     */
    public function getWebsite()
    {
        return new AllopassWebsite($this->_xml->website);
    }
    
    /**
     * Method retrieving the countries
     *
     * @return (AllopassCountry[]) The countries
     */
    public function getCountries()
    {
        $countries = array();
        
        foreach ($this->_xml->countries->children() as $child) {
            foreach ($child->children() as $cChild) {
                $countries[] = new AllopassCountry($cChild);
            }
        }
        
        return $countries;
    }
    
    /**
     * Method retrieving the regions
     *
     * @return (AllopassRegion[]) The regions
     */
    public function getRegions()
    {
        $regions = array();
        
        foreach ($this->_xml->countries->children() as $child) {
            $regions[] = new AllopassRegion($child);
        }
        
        return $regions;
    }
    
    /**
     * Method retrieving the markets
     *
     * @return (AllopassMarket[]) The markets
     */
    public function getMarkets()
    {
        $markets = array();
        
        foreach ($this->_xml->markets->children() as $child) {
            $markets[] = new AllopassMarket($child);
        }
        
        return $markets;
    }
}