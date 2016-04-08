<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use Psr\Http\Message\ResponseInterface;
use vipnytt\XRobotsTagParser;

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
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $userAgent
     * @throws XRobotsTagParser\Exceptions\XRobotsTagParserException
     */
    public function __construct(ResponseInterface $response, $userAgent = '')
    {
        if (!$response instanceof ResponseInterface) {
            throw new XRobotsTagParser\Exceptions\XrobotsTagParserException('Object is not an instance of `\Psr\Http\Message\ResponseInterface`');
        }
        parent::__construct($userAgent);
        $headers = [];
        foreach ($response->getHeader(parent::HEADER_RULE_IDENTIFIER) as $name => $values) {
            $headers[] = $name . ': ' . implode(' ', $values) . "\r\n";
        }
        $this->parse($headers);
    }
}
