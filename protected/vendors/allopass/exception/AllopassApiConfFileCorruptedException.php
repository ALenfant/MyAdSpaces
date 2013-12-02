<?php
/**
 * @file AllopassApiConfFileCorruptedException.php
 * File of the class AllopassApiConfFileCorruptedException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception for a bad formatted or corrupted configuration file
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiConfFileCorruptedException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 6;
    /**
     * Exception definition
     */
    const MESSAGE = 'Configuration file is corrupted or bad formatted';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }
}