<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class exportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Export
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param string $headers
     */
    public function testExport($url, $bot, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains('noindex', $parser->export()['googlebot']);
        $this->assertContains('noarchive', $parser->export()['googlebot']);

        $this->assertContains('noindex', $parser->export()['bingbot']);
        $this->assertContains('noodp', $parser->export()['bingbot']);

        $this->assertContains('noindex', $parser->export()['robot']);
        $this->assertContains('noodp', $parser->export()['robot']);
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
