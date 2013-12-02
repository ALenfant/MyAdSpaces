<?php
/**
 * @file AllopassApiConf.php
 * File of the class AllopassApiConf
 */

require_once dirname(__FILE__) . '/../tools/AllopassApiTools.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiConfFileMissingException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiConfFileCorruptedException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiConfAccountNotFoundException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiConfFileMissingSectionException.php';

/**
 * Class providing convenient tools to access configuration data
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiConf
{
    /**
     * Relative path of the configuration file
     */
    const FILE_PATH = '../conf/conf.xml';
    
    
    /**
     * (ApiConf) Static instance of the class
     */
    private static $_instance = null;
    
    /**
     * (SimpleXMLElement) SimpleXML object representation of the configuration file
     */
    private $_xml;
    
    /**
     * Constructor
     */
    private function __construct()
    {
        $this->_loadFile();
        $this->_checkFile();
    }
    
    /**
     * Internal method processing configuration file loading
     *
     * @throws AllopassApiConfFileMissingException If local API configuration file doesn't exist
     *
     * @throws AllopassApiConfFileCorruptedException If local API configuration file doensn't contain valid XML
     */
    private function _loadFile()
    {
        $path = dirname(__FILE__) . '/' . self::FILE_PATH;

        if (!file_exists($path)) {
            throw new AllopassApiConfFileMissingException();
        }
        
        $this->_xml = AllopassApiTools::xmlParseFile($path);
        
        if (!is_object($this->_xml)) {
            throw new AllopassApiConfFileCorruptedException();
        }
    }
    
    /**
     * Internal method processing configuration file checking
     *
     * @throws AllopassApiConfFileMissingSectionException If local API configuration file doesn't contain every required sections
     */
    private function _checkFile()
    {
        static $sections = array('accounts', 'default_hash', 'default_format', 'network_timeout',
                                 'network_protocol', 'network_port', 'host');
        
        $fileSections = array();
        
        foreach ($this->_xml as $section) {
            $fileSections[] = $section->getName();
        }
        
        foreach ($sections as &$section) {
            if (!in_array($section, $fileSections)) {
                throw new AllopassApiConfFileMissingSectionException();
            }
        }
    }
    
    /**
     * Method retrieving the API key
     *
     * @param email (string) The mail account from which retrieve the API key
     * If email isn't provided or null, the first account is considered
     *
     * @return (string) The public API key
     */
    public function getApiKey($email = null)
    {
        return (string) $this->_retrieveAccount($email)->keys->api_key;
    }
    
    /**
     * Method retrieving the private key
     *
     * @param email (string) The mail account from which retrieve the private key
     * If email isn't provided or null, the first account is considered
     *
     * @return (string) The private API key
     */
    public function getPrivateKey($email = null)
    {
        return (string) $this->_retrieveAccount($email)->keys->private_key;
    }
    
    /**
     * Internal method retrieving an account from its email
     *
     * @param email (string) The mail account from which retrieve the account
     * If email is null, the first account is considered
     *
     * @return (SimpleXMLElement) The SimpleXML representation of the account
     *
     * @throws AllopassApiConfAccountNotFoundException If an email is provided but can't be found
     */
    private function _retrieveAccount($email) {
        $accounts = $this->_xml->accounts->children();

        
        if ($email === null) {
            return $accounts[0];
        }
        else {
            foreach ($accounts as $account) {
                if ($account->attributes()->email == $email) {
                    return $account;
                }
            }
            
            throw new AllopassApiConfAccountNotFoundException();
        }
    }
    
    /**
     * Method retrieving the default response format 
     *
     * @return (string) The response format
     */
    public function getDefaultFormat()
    {
        return (string) $this->_xml->default_format;
    }
    
    /**
     * Method retrieving the API hostname
     *
     * @return (string) The API hostname
     */
    public function getHost()
    {
        return (string) $this->_xml->host;
    }
    
    /**
     * Method retrieving the default hash function name
     *
     * @return (string) The default hash function name
     */
    public function getDefaultHash()
    {
        return (string) $this->_xml->default_hash;
    }
    
    /**
     * Method retrieving the network timeout delay
     *
     * @return (integer) The network timeout delay
     */
    public function getNetworkTimeout()
    {
        return (integer) $this->_xml->network_timeout;
    }
    
    /**
     * Method retrieving the network protocol
     *
     * @return (string) The network protocol
     */
    public function getNetworkProtocol()
    {
        return (string) $this->_xml->network_protocol;
    }
    
    /**
     * Method retrieving the network port
     *
     * @return (integer) The network port
     */
    public function getNetworkPort()
    {
        return (integer) $this->_xml->network_port;
    }
    
    /**
     * Static method providing a single access-point to the class
     *
     * @return (AllopassApiConf) The class instance
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
}