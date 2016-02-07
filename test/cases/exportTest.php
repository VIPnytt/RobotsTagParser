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
     * @param bool $strict
     * @param array|null $headers
     */
    public function testExport($url, $bot, $strict, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $strict, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains(['noindex' => true], $parser->export()['googlebot']);
        $this->assertContains(['noarchive' => true], $parser->export()['googlebot']);

        $this->assertContains(['noindex' => true], $parser->export()['bingbot']);
        $this->assertContains(['noodp' => true], $parser->export()['bingbot']);

        $this->assertContains(['noindex' => true], $parser->export()['']);
        $this->assertContains(['noodp' => true], $parser->export()['']);
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
