<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use vipnytt\XRobotsTagParser;

/**
 * Class Url
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
     * @throws XRobotsTagParser\Exceptions\XRobotsTagParserException
     */
    public function __construct($url, $userAgent = '')
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new XRobotsTagParser\Exceptions\XRobotsTagParserException('Invalid URL provided');
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
            $request = $client->request('GET', $url);
            parent::__construct($request, $userAgent);
        } catch (TransferException $e) {
            throw new XRobotsTagParser\Exceptions\XRobotsTagParserException($e->getMessage());
        }
    }
}
