<?php

require_once dirname(__FILE__).'/AllopassCarrier.php';

/**
 * @file AllopassSubscriptionPayouts.php
 * File of the class AllopassSubscriptionPayouts
 */

/**
 * Class providing object mapping of a Subscription payout item
 *
 * @author Mathieu ROBERT <mrobert@hi-media.com>
 *
 * @date 2012 (c) Hi-media
 */
class AllopassSubscriptionPayouts
{
    /**
     * (SimpleXMLElement) SimpleXML object representation of the item
     */
    private $_xml;
    
    /** 
     * (Array) Current payouts items 
     */
    private $_currentItem = array();
    
    /**
     * Constructor
     *
     * @param xml (SimpleXMLElement) The SimpleXML object representation of the item
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->_xml = $xml;
        
        foreach($this->_xml->children() as $node) {
            $attributes = $node->attributes();
            $this->_currentItem[] = new AllopassCarrier($this->_xml, $attributes->id, $attributes->name, $attributes->amount, $attributes->currency);
        }        
    }
    
    /**
     * Method retrieving the payout currency
     *
     * @return (Array) The payout currency
     */
    public function getValue()
    {
        return $this->_currentItem;
    }
    
    /**
     * Return a specific payout for a carrier Id
     *
     * @return (AllopassCarrier), the currrent AllopassCarrier or null if nothing was found
     */
    public function getSpecificPayouts($carrierId)
    {
        if (!empty($this->_currentItem)) {
            foreach($this->_currentItem as $key => $value) {
                if ($value->id == $carrierId) {
                    return $value;
                }
            }
        } else {
            foreach($this->_xml->children() as $node) {
                $attributes = $node->attributes();
                
                if ((int)$attibutes->id == $carrierId) {
                    return new AllopassCarrier($this->_xml, $attributes->id, $attributes->name, $attributes->amount, $attributes->currency);
                }
            } 
        }
        
        return null;
    }
}