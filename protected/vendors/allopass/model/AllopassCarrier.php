<?php

/**
 * @file AllopassCarrier.php
 * File of the class AllopassPayout
 */

/**
 * Class providing object mapping of a AllopassCarrier item
 *
 * @author Mathieu ROBERT <mrobert@hi-media.com>
 *
 * @date 20012 (c) Hi-media
 */

class AllopassCarrier
{
    /**
     * (SimpleXMLElement) SimpleXML object representation of the item
     */
    private $_xml;
    
    /**
     * (Integer) the current carrier identifier
     */
    private $_id;
    
    /**
     * (String) the current carrier name
     */
    private $_name;
    
    /**
     * (Float) the current payout amount
     */
    private $_payoutAmount;
    
    /**
     * (String), the current payout currency
     */
    private $_payoutCurrency;

    /**
     * Constructor
     *
     * @param xml (SimpleXMLElement) The SimpleXML object representation of the item
     */
    public function __construct(SimpleXMLElement $xml, $carrierId = null, $carrierName = null, $carrierPayoutAmount = null, $carrierPayoutCurrency = null)
    {
        $this->_xml             = $xml;

        if (!empty($carrierId) && !empty($carrierName)) {
            $this->_id              = (int)$carrierId;
            $this->_name            = $carrierName;
        } else {
            $this->_id              = (string)$this->_xml->attributes()->id;
            $this->_name            = (string)$this->_xml->attributes()->name;            
        }
        $this->_payoutAmount    = (float)$carrierPayoutAmount;
        $this->_payoutCurrency  = strtoupper($carrierPayoutCurrency);
    }
    
    /**
     * Return the current carrier identifier
     * (Integer), the current carrier identifier
     */
    public function getId()
    {
        return (int)$this->_id;
    }

    /**
     * Return the current carrier name
     * (String), the current carrier name
     */    
    public function getName()
    {
        return (string)$this->_name;
    }

    /**
     * Return the current carrier payout value
     * (Float), the current carrier payout value
     */      
    public function getPayoutAmount()
    {
        return (float)$this->_payoutAmount;
    }

    /**
     * Return the current carrier payout currency value
     * (Float), the current carrier payout currency value
     */    
    public function getPayoutCurrency()
    {
        return strtoupper((string)$this->_payoutCurrency);
    }
}