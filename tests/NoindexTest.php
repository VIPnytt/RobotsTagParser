<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

class NoindexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * noindex test
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $userAgent
     * @param array $options
     */
    public function testNoIndex($url, $userAgent, $options)
    {
        $parser = new XRobotsTagParser($url, $userAgent, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules(true)['noindex']);
        $this->assertTrue($parser->getRules(false)['noindex']);
        $this->assertTrue($parser->getRules(false)['noarchive']);

        $this->assertTrue(is_string($parser->getDirectiveMeaning('noindex')));
        $this->assertTrue(strlen($parser->getDirectiveMeaning('noindex')) > 30);
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
                        'X-Robots-Tag: noindex'
                    ]
                ]
            ]
        ];
    }
}
