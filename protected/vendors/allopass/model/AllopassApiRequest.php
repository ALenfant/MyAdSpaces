<?php
/**
 * @file AllopassApiRequest.php
 * File of the abstract class AllopassApiRequest
 */

require_once dirname(__FILE__) . '/../tools/AllopassApiTools.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiUnavailableRessourceException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiMissingHashFeatureException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiMissingNetworkFeatureException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiMissingCompressionFeatureException.php';
require_once dirname(__FILE__) . '/../exception/AllopassApiWrongFormatResponseException.php';
require_once dirname(__FILE__) . '/AllopassApiConf.php';
require_once dirname(__FILE__) . '/AllopassApiPlainResponse.php';

/**
 * Class defining the basis of an API request and providing convenient tools
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
abstract class AllopassApiRequest 
{
    /**
     * The response format needed to provide response object mapping
     */
    const MAPPING_FORMAT = 'xml';
    
    /**
     * The API remote path
     */
    const API_PATH = '/rest/';
    
    /**
     * The HTTP connector
     */
    const HTTP_CONNECTOR = '://';
    
     /**
     * The HTTP query string connector
     */
    const HTTP_QUERY_CONNECTOR = '?';
    
    /**
     * The HTTP carriage return line feed
     */
    const HTTP_CRLF = "\r\n";
    
    /**
     * The HTTP version
     */
    const HTTP_VERSION = '1.1';
    
    /**
     * The HTTP chunk size
     */
    const HTTP_CHUNK_SIZE = 4096;
    
    /**
     * The HTTP size of separator between headers and data
     */
    const HTTP_HEADER_SEPARATOR_SIZE = 4;
    
    /**
     * The HTTP specific user agent header
     */
    const HTTP_USER_AGENT = 'Allopass-ApiKit-PHP5';
    
    
    /**
     * (array) Query string parameters of the API call
     */
    protected $_parameters;
    
    /**
     * (boolean) If the request call has to return an object mapped response
     */
    protected $_mapping;
    
    /**
     * (string) Email of the configurated account
     */
    protected $_emailAccount;
    
    /**
     * Provide a way to get the route of each child request
     * 
     * @return (string) The route of the request
     */
    abstract protected function _getPath();
    
    /**
     * Provide a way to get the wired response of each child request
     *
     * @param signature (string) Expected response signature
     * @param headers (string) HTTP headers of the response
     * @param body (string) Raw data of the response
     *
     * @return (AllopassApiResponse) A new response regarding the type of the request
     */
    abstract protected function _newResponse($signature, $headers, $body);
    
    /**
     * Constructor
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     * @param emailAccount (string) Email of the configurated account
     */
    public function __construct(array $parameters, $mapping = true, $emailAccount = null)
    {
        $this->_mapping = $mapping;
        parse_str(http_build_query($parameters), $this->_parameters);
        $this->_emailAccount = $emailAccount;
    }
    
    /**
     * Method to call the request and get its response
     *
     * @return (AllopassApiResponse) The request response
     */
    public function call()
    {
        list($headers, $body) = $this->_buildParameters()->_sign()->_call();
        $signature = $this->_hash($body . AllopassApiConf::getInstance()->getPrivateKey($this->_emailAccount));
        
        return ($this->_mapping) ? $this->_newResponse($signature, $headers, $body)
                                 : new AllopassApiPlainResponse($signature, $headers, $body);
    }
    
    /**
     * Internal method to build special required API parameters
     *
     * @return (AllopassApiRequest) The class instance
     */
    protected function _buildParameters()
    {
        static $formats = array('json', 'xml');
        
        $this->_parameters['api_ts']  = time();
        $this->_parameters['api_key'] = AllopassApiConf::getInstance()->getApiKey($this->_emailAccount);
        $this->_parameters['api_hash'] = AllopassApiConf::getInstance()->getDefaultHash();
        
        if (isset($this->_parameters['format'])) {
            if ($this->_mapping) {
                $this->_parameters['format'] = self::MAPPING_FORMAT;
            }
            elseif (!in_array($this->_parameters['format'], $formats)) {
                $this->_parameters['format'] = AllopassApiConf::getInstance()->getDefaultFormat();
            }
        }
        else {
            $this->_parameters['format'] = AllopassApiConf::getInstance()->getDefaultFormat();
        }
        
        return $this;
    }
    
    /**
     * Internal method to sign the request call
     *
     * @return (AllopassApiRequest) The class instance
     */
    protected function _sign()
    {
        $sign = '';
        
        ksort($this->_parameters);
        
        foreach ($this->_parameters as $key => &$value) {
            $sign .= $key;
            $sign .= (is_array($value)) ? implode($value) : $value;
        }

        $this->_parameters['api_sig'] = $this->_hash($sign . AllopassApiConf::getInstance()->getPrivateKey($this->_emailAccount));
        
        return $this;
    }
    
    /**
     * Internal method to hash data with the defined cipher
     *
     * @param data (string) Data to be hashed
     *
     * @return (string) The hashed data
     *
     * @throws AllopassApiMissingHashFeatureException If configured cipher (SHA1) API isn't loaded/supported
     */
    protected function _hash($data)
    {
        $cipher = AllopassApiConf::getInstance()->getDefaultHash();
        
        if (!function_exists($cipher)) {
            throw new AllopassApiMissingHashFeatureException();
        }
        
        return call_user_func($cipher, $data);
    }
    
    /**
     * Internal method which determinates if the request has to be done using POST
     * 
     * @return (boolean) If the request has to be done using POST
     */
    protected function _isHttpPost()
    {
        return false;
    }
    
    /**
     * Internal method which tries to call remote API
     * 
     * @return (string[]) A 0-indexed array which contains response headers and body
     *
     * @throws AllopassApiMissingNetworkFeatureException If there is neither cURL nor fsockopen enabled/available
     */
    protected function _call() {
        if (function_exists('curl_init')) {
            return $this->_curlCall();
        }
        elseif (function_exists('fsockopen')) {
            return $this->_rawCall();
        }
        else {
            throw new AllopassApiMissingNetworkFeatureException();
        }
    }
    
    /**
     * Internal method performing a raw request using socket
     * 
     * @return (string[]) A 0-indexed array which contains response headers and body
     *
     * @throws AllopassApiUnavailableRessourceException If raw call to remote API fails
     */
    protected function _rawCall()
    {
        $host = AllopassApiConf::getInstance()->getHost();
        
        if (($socket = @fsockopen($host, AllopassApiConf::getInstance()->getNetworkPort(), $socket_errno, $socket_errstr, AllopassApiConf::getInstance()->getNetworkTimeout())) === false) {
            throw new AllopassApiUnavailableRessourceException();
        }
        
        $path = AllopassApiConf::getInstance()->getHost() . self::API_PATH . $this->_getPath();
        
        if ($this->_isHttpPost()) {
            $method = 'POST';          
            $data = http_build_query($this->_parameters);
            $length = strlen($data);
        }
        else {
            $method = 'GET';
            $data = '';
            $length = 0;
            $path .=  self::HTTP_QUERY_CONNECTOR . http_build_query($this->_parameters);
        }
        
        $headers = array();
        $headers[] = 'Host: ' . $host;
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'User-Agent: ' . self::HTTP_USER_AGENT;
        $headers[] = 'Content-Length: ' . $length;
        $headers[] = 'Connection: close';
        
        $request = $method . ' ' . AllopassApiConf::getInstance()->getNetworkProtocol() . self::HTTP_CONNECTOR;
        $request .= $path . ' HTTP/' . self::HTTP_VERSION . self::HTTP_CRLF;
        $request .= implode(self::HTTP_CRLF, $headers) . self::HTTP_CRLF;
        $request .= self::HTTP_CRLF . $data;        

        if (fwrite($socket, $request, strlen($request)) === false) {
            throw new AllopassApiUnavailableRessourceException();
        }
        
        $content = '';

        while (!feof($socket)) {
            if (($line = fread($socket, self::HTTP_CHUNK_SIZE)) === false) {
                throw new AllopassApiUnavailableRessourceException();
            }
            
            $content .= $line;
        }
        
        fclose($socket);
        
        $pos = strpos($content, self::HTTP_CRLF . self::HTTP_CRLF);
        
        if ($pos === false) {
            throw new AllopassApiUnavailableRessourceException();
        }
        
        $headers = substr($content, 0, $pos);
        $body = substr($content, $pos + self::HTTP_HEADER_SEPARATOR_SIZE);
        
        $aHeaders = explode(self::HTTP_CRLF, $headers);
        
        foreach ($aHeaders as &$header) {
            if (AllopassApiTools::beginsWith($header, 'Transfer-Encoding') && AllopassApiTools::endsWith($header, 'chunked')) {
                $body = $this->_decodeChunkedBody($body);
                
                continue;
            }
            
            if (AllopassApiTools::beginsWith($header, 'Content-Encoding')) {
                if (AllopassApiTools::endsWith($header, 'gzip')) {
                    $body = $this->_ungzipBody($body);
                }
                elseif (AllopassApiTools::endsWith($header, 'deflate')) {
                    $body = $this->_inflateBody($body);
                }
            }   
        }
        
        return array($headers, $body);
    }
    
    /**
     * Decode a gzipped content-encoded body
     *
     * @param body (string) The gzipped HTTP body
     *
     * @return (string) The ungzipped response body
     *
     * @throws AllopassApiMissingCompressionFeatureException If gzip inflate function isn't loaded
     */
    protected function _ungzipBody($body)
    {
        if (!function_exists('gzinflate')) {
            throw new AllopassApiMissingCompressionFeatureException();
        }
        
        return gzinflate(substr($body, 10));
    }
    
    /**
     * Decode a deflated content-encoded body
     *
     * @param body (string) The deflated HTTP body
     *
     * @return (string) The inflated response body
     *
     * @throws AllopassApiMissingCompressionFeatureException If gzip uncompress function isn't loaded
     */
    protected function _inflateBody($body)
    {
        if (!function_exists('gzuncompress')) {
            throw new AllopassApiMissingCompressionFeatureException();
        }
        
        return gzuncompress($body);
    }
    
    /**
     * Decode a "chunked" transfer-encoded body
     *
     * @param body (string) The chunked HTTP body
     *
     * @return (string) The response body "chunk decoded"
     *
     * @throws AllopassApiWrongFormatResponseException If response body is not chunked whereas headers say so
     */
    protected function _decodeChunkedBody($body)
    {
        $decBody = '';
        $tokens = array();

        while (trim($body)) {
            if (!preg_match('/^([\da-fA-F]+)[^\r\n]*\r\n/sm', $body, $tokens)) {
                throw new AllopassApiWrongFormatResponseException();
            }

            $length = hexdec(trim($tokens[1]));
            $cut = strlen($tokens[0]);

            $decBody .= substr($body, $cut, $length);
            $body = substr($body, $cut + $length + 2);
        }
        
        return $decBody;
    }
    
    /**
     * Internal method performing a cURL request
     * 
     * @return (string[]) A 0-indexed array which contains response headers and body
     *
     * @throws AllopassApiUnavailableRessourceException If cURL call to remote API fails
     */
    protected function _curlCall()
    {
        $url = AllopassApiConf::getInstance()->getNetworkProtocol() . self::HTTP_CONNECTOR;
        $url .= AllopassApiConf::getInstance()->getHost() . self::API_PATH . $this->_getPath();
        
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_TIMEOUT, AllopassApiConf::getInstance()->getNetworkTimeout());
        curl_setopt($curl, CURLOPT_PORT,    AllopassApiConf::getInstance()->getNetworkPort());
        curl_setopt($curl, CURLOPT_HEADER,  true);
        curl_setopt($curl, CURLOPT_USERAGENT, self::HTTP_USER_AGENT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        if ($this->_isHttpPost()) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->_parameters));
        }
        else {
            $url .= self::HTTP_QUERY_CONNECTOR . http_build_query($this->_parameters);
        }
        
        curl_setopt($curl, CURLOPT_URL, $url);

        $content = @curl_exec($curl);
        
        if (curl_errno($curl) > 0 || $content === false || $content == '') {
            throw new AllopassApiUnavailableRessourceException();
        }
        
        $headerSize  = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        curl_close($curl);
        
        $headers = substr($content, 0, $headerSize - self::HTTP_HEADER_SEPARATOR_SIZE);
        $body = substr($content, $headerSize);
        
        return array($headers, $body);
    }
}