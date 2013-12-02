<?php
/**
 * @file AllopassApiMissingNetworkFeatureException.php
 * File of the class AllopassApiMissingNetworkFeatureException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception if local system supports neither cURL nor fsockopen
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiMissingNetworkFeatureException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 9;
    /**
     * Exception definition
     */
    const MESSAGE = 'Your local PHP system supports neither cURL nor fsockopen';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }
}