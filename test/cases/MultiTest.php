<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class MultiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Multi directives test
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param bool $strict
     * @param array|null $headers
     */
    public function testMultipleDirectives($url, $bot, $strict, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $strict, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains(['noindex' => true], $parser->getRules());
        $this->assertContains(['noindex' => true], $parser->export()['']);
        $this->assertContains(['noindex' => true], $parser->export()['googlebot']);

        $this->assertContains(['nofollow' => true], $parser->getRules());
        $this->assertContains(['nofollow' => true], $parser->export()['']);
        $this->assertContains(['nofollow' => true], $parser->export()['googlebot']);

        $this->assertContains(['noarchive' => true], $parser->getRules());
        $this->assertContains(['noarchive' => true], $parser->export()['']);
        $this->assertContains(['noarchive' => true], $parser->export()['googlebot']);

        $this->assertContains(['nosnippet' => true], $parser->getRules());
        $this->assertContains(['nosnippet' => true], $parser->export()['']);
        $this->assertContains(['nosnippet' => true], $parser->export()['googlebot']);

        $this->assertContains(['noodp' => true], $parser->getRules());
        $this->assertContains(['noodp' => true], $parser->export()['']);
        $this->assertContains(['noodp' => true], $parser->export()['googlebot']);

        $this->assertContains(['notranslate' => true], $parser->getRules());
        $this->assertContains(['notranslate' => true], $parser->export()['']);
        $this->assertContains(['notranslate' => true], $parser->export()['googlebot']);

        $this->assertContains(['noimageindex' => true], $parser->getRules());
        $this->assertContains(['noimageindex' => true], $parser->export()['']);
        $this->assertContains(['noimageindex' => true], $parser->export()['googlebot']);
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
                    'HTTP/1.1 200 OK',
                    'Date: Tue, 25 May 2010 21:42:43 GMT',
                    'X-Robots-Tag: all',
                    'X-Robots-Tag: noindex',
                    'X-Robots-Tag: nofollow',
                    'X-Robots-Tag: none',
                    'X-Robots-Tag: noarchive',
                    'X-Robots-Tag: nosnippet',
                    'X-Robots-Tag: noodp',
                    'X-Robots-Tag: notranslate',
                    'X-Robots-Tag: noimageindex',
                    'X-Robots-Tag: unavailable_after: 25 Jun 2010 15:00:00 PST',
                    'X-Robots-Tag: googlebot: all, none, nofollow,nosnippet,notranslate unavailable_after: 25 Jun 2010 15:00:00 PST, noindex, noarchive, noodp,noimageindex'
                ]
            ]
        ];
    }
}
