<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class UnavailableAfterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * unavailable_after test
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param array $options
     */
    public function testUnavailableAfter($url, $bot, $options)
    {
        $parser = new XRobotsTagParser($url, $bot, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertEquals(['unavailable_after' => 'Saturday, 01-Jul-00 07:00:00 PST'], $parser->getRules(true));
        $this->assertTrue($parser->getRules(false)['noindex']);
        $this->assertEquals(['unavailable_after' => 'Tuesday, 31-Dec-30 23:00:00 PST'], $parser->export()['']);
        $this->assertEquals(['unavailable_after' => 'Saturday, 01-Jul-00 07:00:00 PST'], $parser->export()['googlebot']);
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
                        'X-Robots-Tag: unavailable_after: Tuesday, 31-Dec-30 23:00:00 PST',
                        'X-Robots-Tag: googlebot: unavailable_after: Saturday, 01-Jul-00 07:00:00 PST'
                    ]
                ]
            ],
            [
                'http://example.com/',
                'googlebot',
                ['headers' =>
                    [
                        'X-Robots-Tag: unavailable_after: 31 Dec 2030 23:00:00 PST',
                        'X-Robots-Tag: googlebot: unavailable_after: 01 Jul 2000 07:00:00 PST'
                    ]
                ]
            ]
        ];
    }
}
