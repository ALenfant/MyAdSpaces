<?php
/**
 * @file AllopassApiTools.php
 * File providing convenient tool methods
 */

require_once dirname(__FILE__) . '/../exception/AllopassApiMissingXMLFeatureException.php';

/**
 * Class providing convenient tools
 *
 * @author Jérémy Langlais <jlanglais@hi-media.com>
 *
 * @date 2009 (c) Hi-media
 */
class AllopassApiTools
{
    /**
    * Checks if a string begins with another one 
    *
    * @param haystack (string) The containing string
    * @param needle (string) The expected contained string
    *
    * @return (boolean) If the containing string begins with the contained string
    */
    public static function beginsWith($haystack, $needle)
    {
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);

        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    /**
    * Checks if a string ends with another one 
    *
    * @param haystack (string) The containing string
    * @param needle (string) The expected contained string
    *
    * @return (boolean) If the containing string ends with the contained string
    */
    public static function endsWith($haystack, $needle)
    {
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);

        return (substr($haystack, -strlen($needle)) == $needle);
    }
    /**
     * Checks if SimpleXML library is available
     *
     * @throws AllopassApiMissingXMLFeatureException If SimpleXML API isn't loaded/supported
     */
    private static function _checkXmlParser()
    {
        if (!function_exists('simplexml_load_string') || !class_exists('SimpleXMLElement')) {
            throw new AllopassApiMissingXMLFeatureException();
        }
    }
    
    /**
     * Load an XML string into a mapping object
     *
     * @param string (string) The XML string to be parsed
     *
     * @return (SimpleXMLElement) The mapping object
     */
    public static function xmlParseString($string)
    {
        self::_checkXmlParser();
        
        return @simplexml_load_string($string);
    }
    
    /**
     * Load an XML file into a mapping object
     *
     * @param path (string) The XML file path
     *
     * @return (SimpleXMLElement) The mapping object
     */
    public static function xmlParseFile($path)
    {
        self::_checkXmlParser();
        
        return @simplexml_load_file($path);
    }
}