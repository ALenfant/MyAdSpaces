<?php
/**
 * @file AllopassApiConfFileMissingException.php
 * File of the class AllopassApiConfFileMissingException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception for a missing configuration file
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiConfFileMissingException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 5;
    /**
     * Exception definition
     */
    const MESSAGE = 'Configuration file is missing';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }
}