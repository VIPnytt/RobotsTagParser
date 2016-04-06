<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

class GetRulesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get rules
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param array $options
     */
    public function testGetRules($url, $bot, $options)
    {
        $parser = new XRobotsTagParser($url, $bot, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules(true)['noindex']);
        $this->assertTrue($parser->getRules(true)['noarchive']);
        $this->assertTrue($parser->getRules(true)['noodp']);
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
