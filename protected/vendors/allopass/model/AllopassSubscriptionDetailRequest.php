<?php
/**
 * @file AllopassSubscriptionDetailRequest.php
 * File of the class AllopassSubscriptionDetailRequest
 */

require_once dirname(__FILE__) . '/AllopassApiRequest.php';
require_once dirname(__FILE__) . '/AllopassSubscriptionDetailResponse.php';

/**
 * Class providing a subscription detail API request
 *
 * @author Mathieu Robert <mrobert@hi-media.com>
 *
 * @date 2011 (c) Hi-media
 */
class AllopassSubscriptionDetailRequest extends AllopassApiRequest
{
    /**
     * Route path of the API
     */
    const PATH = 'subscription';
    
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
     * @return (AllopassSubscriptionDetailResponse) A new response
     */
    protected function _newResponse($signature, $headers, $body)
    {
        return new AllopassSubscriptionDetailResponse($signature, $headers, $body);
    }
}