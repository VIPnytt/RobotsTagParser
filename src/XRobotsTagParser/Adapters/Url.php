<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use GuzzleHttp\Client;
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
     * GuzzleHttp config
     * @var array
     */
    protected $config = [
        'headers' => [
            'User-Agent' => 'X-Robots-Tag-parser-VIPnytt/1.0 (+https://github.com/VIPnytt/X-Robots-Tag-parser/blob/master/README.md)',
        ],
        'http_errors' => false,
        'timeout' => 30,
    ];

    /**
     * Constructor
     *
     * @param string $url
     * @param string $userAgent
     * @throws XRobotsTagParser\Exceptions\XRobotsTagParserException
     */
    public function __construct($url, $userAgent = '')
    {
        if (!empty($userAgent)) {
            $this->config['headers']['User-Agent'] = $userAgent;
        }
        $client = new Client($this->config);
        $request = $client->request('GET', $url);
        parent::__construct($request, $userAgent);
    }
}
