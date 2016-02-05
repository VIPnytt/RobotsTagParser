<?php

namespace vipnytt\XRobotsTagParser\tests;

use vipnytt\XRobotsTagParser;

class MultiDirectivesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Directives tests
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $bot
     * @param string $headers
     */
    public function export($url, $bot, $headers)
    {
        $parser = new XRobotsTagParser($url, $bot, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertContains('noindex', $parser->getRules());
        $this->assertContains('noindex', $parser->export()['robots']);
        $this->assertContains('noindex', $parser->export()['googlebot']);
        $this->assertContains('nofollow', $parser->getRules());
        $this->assertContains('nofollow', $parser->export()['robots']);
        $this->assertContains('nofollow', $parser->export()['googlebot']);
        $this->assertContains('noarchive', $parser->getRules());
        $this->assertContains('noarchive', $parser->export()['robots']);
        $this->assertContains('noarchive', $parser->export()['googlebot']);
        $this->assertContains('nosnippet', $parser->getRules());
        $this->assertContains('nosnippet', $parser->export()['robots']);
        $this->assertContains('nosnippet', $parser->export()['googlebot']);
        $this->assertContains('noodp', $parser->getRules());
        $this->assertContains('noodp', $parser->export()['robots']);
        $this->assertContains('noodp', $parser->export()['googlebot']);
        $this->assertContains('notranslate', $parser->getRules());
        $this->assertContains('notranslate', $parser->export()['robots']);
        $this->assertContains('notranslate', $parser->export()['googlebot']);
        $this->assertContains('noimageindex', $parser->getRules());
        $this->assertContains('noimageindex', $parser->export()['robots']);
        $this->assertContains('noimageindex', $parser->export()['googlebot']);
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
        ];
    }
}
