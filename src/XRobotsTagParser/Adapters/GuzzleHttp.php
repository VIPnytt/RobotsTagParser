<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use GuzzleHttp\Psr7\Response;
use vipnytt\XRobotsTagParser;
use vipnytt\XRobotsTagParser\Exceptions\XrobotsTagParserException;

/**
 * Class GuzzleHttp
 *
 * Parse from an \GuzzleHttp\Psr7\Response object
 *
 * @package vipnytt\XRobotsTagParser\Adapters
 */
class GuzzleHttp extends XRobotsTagParser
{
    /**
     * Constructor
     *
     * @param \GuzzleHttp\Psr7\Response $response
     * @param string $userAgent
     * @throws XRobotsTagParserException
     * @throws XRobotsTagParser\Exceptions\XRobotsTagParserException
     */
    public function __construct(Response $response, $userAgent = '')
    {
        if (!$response instanceof Response) {
            throw new XRobotsTagParserException('Object is not an instance of `\GuzzleHttp\Psr7\Response`');
        }
        parent::__construct($userAgent);
        $headers = [];
        foreach ($response->getHeader(parent::HEADER_RULE_IDENTIFIER) as $name => $values) {
            $headers[] = $name . ': ' . implode(' ', $values) . "\r\n";
        }
        $this->parse($headers);
    }
}