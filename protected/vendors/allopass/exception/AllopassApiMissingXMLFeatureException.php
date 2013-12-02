<?php
/**
 * @file AllopassApiMissingXMLFeatureException.php
 * File of the class AllopassApiMissingXMLFeatureException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception if local system doesn't support SimpleXML
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiMissingXMLFeatureException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 7;
    /**
     * Exception definition
     */
    const MESSAGE = 'Your local PHP system doesn\'t support needed SimpleXML library';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }
}