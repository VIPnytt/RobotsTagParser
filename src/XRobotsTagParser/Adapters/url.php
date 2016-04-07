<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use vipnytt\XRobotsTagParser;
use vipnytt\XRobotsTagParser\Exceptions\XrobotsTagParserException;

/**
 * Class url
 *
 * Request the HTTP headers from an URL
 *
 * @package vipnytt\XRobotsTagParser\Adapters
 */
class url extends XRobotsTagParser\Adapters\GuzzleHttp
{
    /**
     * Constructor
     *
     * @param string $url
     * @param string $userAgent
     * @throws XRobotsTagParserException
     */
    public function __construct($url, $userAgent = '')
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new XRobotsTagParserException('Invalid URL provided');
        }
        $config = [];
        if (!empty($userAgent)) {
            $config = [
                'headers' => [
                    'User-Agent' => $userAgent
                ]
            ];
        }
        try {
            $client = new Client($config);
            $request = $client->Request('GET', $url);
            parent::__construct($request, $userAgent);
        } catch (TransferException $e) {
            throw new XRobotsTagParserException($e->getMessage());
        }
    }
}