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
     * @param string $headers
     */
    public function getRules($url, $bot, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains('noindex', $parser->getRules());
        $this->assertContains('noarchive', $parser->getRules());
        $this->assertContains('noodp', $parser->getRules());
    }

    /**
     * Generate test data
     * @return array
     */
    public function generateDataForTest()
    {
        return [
            ['http://example.com/'],
            ['googlebot'],
            [
                'X-Robots-Tag: googlebot: noindex, noarchive',
                'X-Robots-Tag: bingbot: noindex, noodp',
                'X-Robots-Tag: noindex, noodp'
            ]
        ];
    }
}
