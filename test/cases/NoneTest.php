<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * none test
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param array $options
     */
    public function testNone($url, $bot, $options)
    {
        $parser = new XRobotsTagParser($url, $bot, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules(true)['none']);
        $this->assertTrue($parser->getRules(false)['noindex']);
        $this->assertTrue($parser->getRules(false)['nofollow']);
    }

    /**
     * Generate test data
     * @return array
     */
    public function generateDataForTest()
    {
        return [
            [
                'http://example.com/',
                'googlebot',
                ['headers' =>
                    [
                        'X-Robots-Tag: none'
                    ]
                ]
            ]
        ];
    }
}
