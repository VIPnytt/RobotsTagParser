<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * none test
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $userAgent
     * @param array $options
     */
    public function testNone($url, $userAgent, $options)
    {
        $parser = new XRobotsTagParser($url, $userAgent, $options);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules(true)['none']);
        $this->assertTrue($parser->getRules(false)['noindex']);
        $this->assertTrue($parser->getRules(false)['nofollow']);

        $this->assertTrue(is_string($parser->getDirectiveMeaning('none')));
        $this->assertTrue(strlen($parser->getDirectiveMeaning('none')) > 30);
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
                        'X-Robots-Tag: none'
                    ]
                ]
            ]
        ];
    }
}
