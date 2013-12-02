<?php
/**
 * @file AllopassApiRemoteErrorException.php
 * File of the class AllopassApiRemoteErrorException
 */

require_once dirname(__FILE__) . '/AllopassApiException.php';

/**
 * Class of an exception for a exception thrown by remote Allopass API
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiRemoteErrorException extends AllopassApiException
{
    /**
     * Exception code
     */
    const CODE = 4;
    /**
     * Exception definition
     */
    const MESSAGE = 'The response indicates that an error occured when processing the request';
    
    /**
     * Remote exception code
     */
    protected $_remoteCode;
    
    /**
     * Remote exception definition
     */
    protected $_remoteMessage;
    
    /**
     * Constructor
     */
    public function __construct($code, $message)
    {
        parent::__construct(self::MESSAGE, self::CODE);
        
        $this->_remoteCode = $code;
        $this->_remoteMessage = $message;
    }
    
    /**
     * Method retrieving the remote exception code
     * 
     * @return (integer) The remote exception code
     */
    public function getRemoteCode()
    {
        return $this->_remoteCode;
    }
    
    /**
     * Method retrieving the remote exception message
     * 
     * @return (string) The remote exception message
     */
    public function getRemoteMessage()
    {
        return $this->_remoteMessage;
    }
    
    /**
     * Overload of parent __toString magic method
     * 
     * @return (string) Exception string representation containing local and remote exceptions definitions
     */
    public function __toString()
    {
        return "Remote exception with code '{$this->_remoteCode}' and message '{$this->_remoteMessage}'\n\n" . parent::__toString();
    }
}