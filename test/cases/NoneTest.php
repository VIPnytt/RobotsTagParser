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
     * @param bool $strict
     * @param array|null $headers
     */
    public function testNone($url, $bot, $strict, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $strict, $headers);
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
                false,
                [
                    'X-Robots-Tag: none',
                    'X-Robots-Tag: googlebot: none'
                ]
            ]
        ];
    }
}
