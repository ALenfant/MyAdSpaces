<?php
/**
 * @file AllopassApiMappingResponse.php
 * File of the class AllopassApiMappingResponse
 */

require_once dirname(__FILE__) . '/../tools/AllopassApiTools.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiWrongFormatResponseException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiRemoteErrorException.php';
require_once dirname(__FILE__) . '/AllopassApiResponse.php';

/**
 * Class defining an object mapped API response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
abstract class AllopassApiMappingResponse extends AllopassApiResponse
{
    /**
     * (SimpleXMLElement) The SimpleXML object representation of the response
     */
    protected $_xml;
    
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
        
        $this->_xml = AllopassApiTools::xmlParseString($this->_body);
        
        $this->_verify();
    }
    
    /**
     * Overload of parent internal method providing signature verification
     *
     * @throws AllopassApiWrongFormatResponseException If response doesn't contain valid XML
     *
     * @throws AllopassApiRemoteErrorException if response describe a remote API exception
     */
    protected function _verify()
    {
        if (!is_object($this->_xml)) {
            throw new AllopassApiWrongFormatResponseException();
        }

        $attributes = $this->_xml->attributes();
        
        if ((int) $attributes->code != 0) {
            throw new AllopassApiRemoteErrorException($attributes->code, $attributes->message);
        }
        
        parent::_verify();
    }
}