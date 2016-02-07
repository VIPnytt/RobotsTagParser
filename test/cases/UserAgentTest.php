<?php

namespace vipnytt\robot\UserAgentParser\tests;

use vipnytt\XRobotsTagParser\UserAgentParser;

class UserAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Character case
     */
    public function testCharacterCase()
    {
        $parser = new UserAgentParser('GoogleBot');
        $this->assertInstanceOf('vipnytt\XRobotsTagParser\UserAgentParser', $parser);

        $this->assertEquals('googlebot', $parser->match(['GoogleBot']));
    }

    /**
     * Strip version
     */
    public function testStripVersion()
    {
        $parser = new UserAgentParser('googlebot/2.1');
        $this->assertInstanceOf('vipnytt\XRobotsTagParser\UserAgentParser', $parser);

        $this->assertEquals('googlebot', $parser->stripVersion());
    }

    /**
     * Find match
     */
    public function testMatch()
    {
        $parser = new UserAgentParser('googlebot-news');
        $this->assertInstanceOf('vipnytt\XRobotsTagParser\UserAgentParser', $parser);

        $this->assertEquals('googlebot-news', $parser->match(['googlebot', 'googlebot-news', 'google', 'gooblebot-news-unknown'], '*'));
        $this->assertEquals('googlebot', $parser->match(['googlebot', 'bingbot', 'mybot', ''], '*'));
        $this->assertEquals('*', $parser->match(['yandexbot', 'bingbot', 'mybot', ''], '*'));
    }

    /**
     * Get all
     */
    public function testExport()
    {
        $parser = new UserAgentParser('googlebot-news/2.1');
        $this->assertInstanceOf('vipnytt\XRobotsTagParser\UserAgentParser', $parser);

        $this->assertContains('googlebot-news/2.1', $parser->export());
        $this->assertContains('googlebot-news', $parser->export());
        $this->assertContains('googlebot', $parser->export());
    }
}
