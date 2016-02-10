<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Directive: NONE
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

        $this->assertTrue($parser->getRules()['none']);
        $this->assertTrue($parser->getRules()['noindex']);
        $this->assertTrue($parser->getRules()['nofollow']);

        $this->assertTrue($parser->export()['']['none']);
        $this->assertTrue($parser->export()['']['noindex']);
        $this->assertTrue($parser->export()['']['nofollow']);

        $this->assertTrue($parser->export()['googlebot']['none']);
        $this->assertTrue($parser->export()['googlebot']['noindex']);
        $this->assertTrue($parser->export()['googlebot']['nofollow']);
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
                        'X-Robots-Tag: none',
                        'X-Robots-Tag: googlebot: none'
                    ]
                ]
            ]
        ];
    }
}
