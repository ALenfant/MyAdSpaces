<?php
/**
 * @file AllopassApiPlainResponse.php
 * File of the class AllopassApiPlainResponse
 */

require_once dirname(__FILE__) . '/AllopassApiResponse.php';

/**
 * Class defining a plain API response
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiPlainResponse extends AllopassApiResponse
{
    /**
     * Constructor
     *
     * @param signature (string) Expected response signature
     * @param headers (string) HTTP headers of the response
     * @param body (string) Raw data of the response
     */
    public function __construct($signature, $headers, $body)
    {
        parent::__construct($signature, $headers, $body);
    }
}