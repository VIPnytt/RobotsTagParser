<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Directive: NONE
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param bool $strict
     * @param array|null $headers
     */
    public function testNone($url, $bot, $strict, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $strict, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains(['none' => true], $parser->getRules());
        $this->assertContains(['noindex' => true], $parser->getRules());
        $this->assertContains(['nofollow' => true], $parser->getRules());

        $this->assertContains(['none' => true], $parser->export()['']);
        $this->assertContains(['noindex' => true], $parser->export()['']);
        $this->assertContains(['nofollow' => true], $parser->export()['']);

        $this->assertContains(['none' => true], $parser->export()['googlebot']);
        $this->assertContains(['noindex' => true], $parser->export()['googlebot']);
        $this->assertContains(['nofollow' => true], $parser->export()['googlebot']);
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
                    'X-Robots-Tag: none',
                    'X-Robots-Tag: googlebot: none'
                ]
            ]
        ];
    }
}
