<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use vipnytt\XRobotsTagParser;

/**
 * Class TextString
 *
 * Parse line-separated text string
 *
 * @package vipnytt\XRobotsTagParser\Adapters
 */
class TextString extends XRobotsTagParser
{
    /**
     * Constructor
     *
     * @param string $string
     * @param string $userAgent
     */
    public function __construct($string, $userAgent = '')
    {
        $array = array_map('trim', preg_split('/\R/', $string));
        parent::__construct($userAgent, $array);
    }
}
