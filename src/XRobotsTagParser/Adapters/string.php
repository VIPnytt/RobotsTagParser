<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use vipnytt\XRobotsTagParser;
use vipnytt\XRobotsTagParser\Exceptions\XrobotsTagParserException;

/**
 * Class string
 *
 * Parse line-separated string
 *
 * @package vipnytt\XRobotsTagParser\Adapters
 */
class string extends XRobotsTagParser
{
    /**
     * Constructor
     *
     * @param string $string
     * @param string $userAgent
     * @throws XRobotsTagParserException
     */
    public function __construct($string, $userAgent = '')
    {
        $array = array_map('trim', preg_split('/\R/', $string));
        parent::__construct($userAgent, $array);
    }
}