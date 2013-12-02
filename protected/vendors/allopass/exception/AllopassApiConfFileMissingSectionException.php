<?php
/**
 * @file AllopassApiConfFileMissingSectionException.php
 * File of the class AllopassApiConfFileMissingSectionException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception if a section is not found into configuration file
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiConfFileMissingSectionException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 12;
    /**
     * Exception definition
     */
    const MESSAGE = 'A required section cannot be found in the configuration file';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }
}