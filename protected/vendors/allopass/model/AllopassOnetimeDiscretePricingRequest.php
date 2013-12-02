<?php
/**
 * @file AllopassOnetimeDiscretePricingRequest.php
 * File of the class AllopassOnetimeDiscretePricingRequest
 */

require_once dirname(__FILE__) . '/AllopassApiRequest.php';
require_once dirname(__FILE__) . '/AllopassOnetimePricingResponse.php';

/**
 * Class providing a onetime discrete pricing API request
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassOnetimeDiscretePricingRequest extends AllopassApiRequest
{
    /**
     * Route path of the API
     */
    const PATH = 'onetime/discrete-pricing';
    
    /**
     * Constructor
     *
     * @param parameters (array) Query string parameters of the API call
     * @param mapping (boolean) Should the response be an object mapping or a plain response
     * @param emailAccount (string) Email of the configurated account
     */
    public function __construct(array $parameters, $mapping = true, $emailAccount = null)
    {
        parent::__construct($parameters, $mapping, $emailAccount);
    }
    
    /**
     * Provide a way to get the route of the request
     * 
     * @return (string) The route of the request
     */
    protected function _getPath()
    {
        return self::PATH;
    }
    
    /**
     * Provide a way to get the wired response of the request
     *
     * @param signature (string) Expected response signature
     * @param headers (string) HTTP headers of the response
     * @param body (string) Raw data of the response
     *
     * @return (AllopassOnetimePricingResponse) A new response
     */
    protected function _newResponse($signature, $headers, $body)
    {
        return new AllopassOnetimePricingResponse($signature, $headers, $body);
    }
}