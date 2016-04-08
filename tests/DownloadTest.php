<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

class DownloadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get rules
     *
     * @dataProvider generateDataForTest
     * @param string $url
     * @param string $userAgent
     */
    public function testDownload($url, $userAgent)
    {
        $parser = new XRobotsTagParser\Adapters\Url($url, $userAgent);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->export() == []);
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
                'MyCustomBot',
            ]
        ];
    }
}
