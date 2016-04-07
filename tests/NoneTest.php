<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * none test
     *
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testNone($userAgent, $headers)
    {
        $parser = new XRobotsTagParser($userAgent, $headers);
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
                'googlebot',
                [
                    'X-Robots-Tag: none'
                ]
            ]
        ];
    }
}
