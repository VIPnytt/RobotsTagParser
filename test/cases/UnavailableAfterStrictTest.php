<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class UnavailableAfterStrictTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Directive: UNAVAILABLE_AFTER
     * Strict: ON
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param array $options
     */
    public function testUnavailableAfterStrict($url, $bot, $options)
    {
        $parser = new XRobotsTagParser($url, $bot, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        // TODO: Disabled due to an RFC-850 parsing bug
        //$this->assertEquals(['unavailable_after' => 'Saturday, 01-Jul-00 07:00:00 PST'], $parser->getRules());
        //$this->assertEquals(['unavailable_after' => 'Saturday, 31-Dec-50 23:00:00 PST'], $parser->export()['']);
        //$this->assertEquals(['unavailable_after' => 'Saturday, 01-Jul-00 07:00:00 PST'], $parser->export()['googlebot']);
        //$this->assertArrayNotHasKey('unavailable_after', $parser->export()['bingbot']);
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
                [
                    'strict' => true,
                    'headers' =>
                        [
                            'X-Robots-Tag: unavailable_after: Saturday, 31-Dec-50 23:00:00 PST',
                            'X-Robots-Tag: googlebot: unavailable_after: Saturday, 01-Jul-00 07:00:00 PST',
                            'X-Robots-Tag: bingbot: unavailable_after: 31 Dec 2050 23:00:00 PST'
                        ]
                ]
            ]
        ];
    }
}
