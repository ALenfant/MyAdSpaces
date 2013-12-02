<?php
/**
 * @file AllopassApiUnavailableRessourceException.php
 * File of the class AllopassApiUnavailableRessourceException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception for an unreachable Allopass API
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiUnavailableRessourceException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 1;
    /**
     * Exception definition
     */
    const MESSAGE = 'The API you are requesting is currently unavailable';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }
}