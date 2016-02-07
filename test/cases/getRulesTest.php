<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class getRulesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get rules
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param bool $strict
     * @param array|null $headers
     */
    public function testGetRules($url, $bot, $strict, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $strict, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules()['noindex']);
        $this->assertTrue($parser->getRules()['noarchive']);
        $this->assertTrue($parser->getRules()['noodp']);
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
                    'X-Robots-Tag: googlebot: noindex, noarchive',
                    'X-Robots-Tag: bingbot: noindex, noodp',
                    'X-Robots-Tag: noindex, noodp'
                ]
            ]
        ];
    }
}
