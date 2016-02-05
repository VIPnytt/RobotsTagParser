<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class DirectiveNoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Directive: NONE
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param string $headers
     */
    public function testNone($url, $bot, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains('none', $parser->getRules());
        $this->assertContains('none', $parser->getRules());
        $this->assertContains('none', $parser->getRules());

        $this->assertContains('noindex', $parser->export()['robots']);
        $this->assertContains('noindex', $parser->export()['robots']);
        $this->assertContains('noindex', $parser->export()['robots']);

        $this->assertContains('nofollow', $parser->export()['googlebot']);
        $this->assertContains('nofollow', $parser->export()['googlebot']);
        $this->assertContains('nofollow', $parser->export()['googlebot']);
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
                'X-Robots-Tag: none',
                'X-Robots-Tag: googlebot: none'
            ]
        ];
    }
}
