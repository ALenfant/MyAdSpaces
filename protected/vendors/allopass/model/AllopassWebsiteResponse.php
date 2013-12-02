<?php
/**
 * @file AllopassWebsiteResponse.php
 * File of the class AllopassWebsiteResponse
 */

require_once dirname(__FILE__) . '/AllopassApiMappingResponse.php';
require_once dirname(__FILE__) . '/AllopassWebsite.php';

/**
 * Class defining a Website request's response
 *
 * @author Jérôme Brissonnet <jbrissonnet@hi-media.com>
 *
 * @date 2012 (c) Hi-media
 */
class AllopassWebsiteResponse extends AllopassApiMappingResponse
{
    public function getWebsites()
    {
        $websites = array();
        if (isset($this->_xml->website)) {
            foreach($this->_xml->website as $xmlWebsite) {
                $websites[] = new AllopassWebsite($xmlWebsite);
            }
        }
        return $websites;
    }
}