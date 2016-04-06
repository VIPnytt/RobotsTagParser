<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

class ExportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Export
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param array $options
     */
    public function testExport($url, $bot, $options)
    {
        $parser = new XRobotsTagParser($url, $bot, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->export()['googlebot']['noindex']);
        $this->assertTrue($parser->export()['googlebot']['noarchive']);

        $this->assertTrue($parser->export()['bingbot']['noindex']);
        $this->assertTrue($parser->export()['bingbot']['noodp']);

        $this->assertTrue($parser->export()['']['noindex']);
        $this->assertTrue($parser->export()['']['noodp']);
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
                        'X-Robots-Tag: googlebot: noindex, noarchive',
                        'X-Robots-Tag: bingbot: noindex, noodp',
                        'X-Robots-Tag: noindex, noodp'
                    ]
                ]
            ]
        ];
    }
}
