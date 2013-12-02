<?php

/**
 * @file AllopassSubscriptionDetailListResponse.php
 * File of the class AllopassSubscriptionDetailListResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';

/**
 * Class defining a subscription detail request's response
 *
 * @author Mathieu Robert <mrobert@hi-media.com>
 *
 * @date 2011 (c) Hi-media
 */
class AllopassSubscriptionListResponse extends AllopassApiMappingResponse
{
    /**
     * Constructor
     * 
     * @param signature (string) Expected response signature
     * @param headers (string) HTTP headers of the response
     * @param body (string) Raw data of the response
     */
    public function __construct($signature, $headers, $body)
    {
        parent::__construct($signature, $headers, $body);
    }
    
    /**
     * Method retrieving the subscriber reference
     *
     * @return (string) The subscriber reference
     */
    public function getSubscriberReference()
    {
        $item =  array();
        
        foreach($this->_xml->subscribers as $key => $value) {
            foreach($value as $keyAttr => $valueAttr) {
                $attributes = $valueAttr->attributes();
            
                $item[] = (string)$attributes->reference;                            
            }
        }
    
        return $item;
    }    
}
