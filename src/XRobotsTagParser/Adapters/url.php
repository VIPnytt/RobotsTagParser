<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use vipnytt\XRobotsTagParser;

/**
 * Class url
 *
 * Request the HTTP headers from an URL
 *
 * @package vipnytt\XRobotsTagParser\Adapters
 */
class Url extends XRobotsTagParser\Adapters\GuzzleHttp
{
    /**
     * Constructor
     *
     * @param string $url
     * @param string $userAgent
     * @throws XRobotsTagParser\Exceptions\XrobotsTagParserException
     */
    public function __construct($url, $userAgent = '')
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new XRobotsTagParser\Exceptions\XrobotsTagParserException('Invalid URL provided');
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
            throw new XRobotsTagParser\Exceptions\XrobotsTagParserException($e->getMessage());
        }
    }
}
