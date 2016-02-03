<?php
/**
 * X-Robots-Tag HTTP header parser class
 *
 * @author VIP nytt (vipnytt@gmail.com)
 * @author Jan-Petter Gundersen (europe.jpg@gmail.com)
 *
 * Specification:
 * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header
 *
 *
 * TODO list:
 * [ ] Detect header User-Agent
 * [x] Parse/split/match input User-Agent
 * [ ] Header input parameter (do not request from URL)
 * [ ] DIRECTIVE_NONE
 * [ ] DIRECTIVE_NO_INDEX
 * [ ] DIRECTIVE_NO_FOLLOW
 * [ ] DIRECTIVE_NO_ARCHIVE
 * [ ] DIRECTIVE_NO_SNIPPET
 * [ ] DIRECTIVE_NO_ODP
 * [ ] DIRECTIVE_NO_TRANSLATE
 * [ ] DIRECTIVE_NO_IMAGE_INDEX
 * [ ] DIRECTIVE_UNAVAILABLE_AFTER
 *
 */

namespace vipnytt;

use GuzzleHttp;

class XRobotsTagParser
{
    // directives
    const DIRECTIVE_ALL = 'all';
    const DIRECTIVE_NONE = 'none';
    const DIRECTIVE_NO_INDEX = 'noindex';
    const DIRECTIVE_NO_FOLLOW = 'nofollow';
    const DIRECTIVE_NO_ARCHIVE = 'noarchive';
    const DIRECTIVE_NO_SNIPPET = 'nosnippet';
    const DIRECTIVE_NO_ODP = 'noodp';
    const DIRECTIVE_NO_TRANSLATE = 'notranslate';
    const DIRECTIVE_NO_IMAGE_INDEX = 'noimageindex';
    const DIRECTIVE_UNAVAILABLE_AFTER = 'unavailable_after';

    const PREFIX = 'x-robots-tag:';

    protected $headers = [];
    protected $rules = [];
    protected $currentUserAgent = "";

    // internally used variables
    private $state = '';
    private $url = '';
    private $userAgent = '*';
    private $userAgent_groups = ['*'];
    private $userAgent_match = '*';

    /**
     * Constructor
     *
     * @param  string $url
     * @param  string $userAgent
     */
    public function __construct($url, $userAgent = '*')
    {

        $this->setUserAgent(trim($userAgent));
        $this->url = $this->encode_url(trim($url));
        $this->request();
        $this->parse();
    }

    /**
     * Set UserAgent
     *
     * @param string $userAgent
     * @return void
     */
    protected function setUserAgent($userAgent)
    {
        $this->userAgent = mb_strtolower($userAgent);
        if (empty($this->userAgent)) {
            $this->userAgent = '*';
        }
        $this->explodeUserAgent();
    }

    /**
     *  Parses all possible userAgent groups to an array
     *
     * @return array
     */
    private function explodeUserAgent()
    {
        $this->userAgent_groups = array($this->userAgent);
        $this->userAgent_groups[] = $this->stripUserAgentVersion($this->userAgent);
        while (strpos(end($this->userAgent_groups), '-') !== false) {
            $current = end($this->userAgent_groups);
            $this->userAgent_groups[] = substr($current, 0, strrpos($current, '-'));
        }
        $this->userAgent_groups[] = '*';
        $this->userAgent_groups = array_unique($this->userAgent_groups);
        $this->determineUserAgentGroup();
    }

    /**
     *  Removes the userAgent version
     *
     * @param string $userAgent
     * @return string
     */
    private static function stripUserAgentVersion($userAgent)
    {
        if (strpos($userAgent, '/') !== false) {
            return explode('/', $userAgent, 2)[0];
        }
        return $userAgent;
    }

    /**
     *  Determine the correct user agent group
     *
     * @return void
     */
    protected function determineUserAgentGroup()
    {
        foreach ($this->userAgent_groups as $group) {
            if (isset($this->rules[$group])) {
                $this->userAgent_match = $group;
                return;
            }
        }
        $this->userAgent_match = '*';
    }

    /**
     * URL encoder according to RFC 3986
     * Returns a string containing the encoded URL with disallowed characters converted to their percentage encodings.
     * @link http://publicmind.in/blog/url-encoding/
     *
     * @param string $url
     * @return string string
     */
    protected function encode_url($url)
    {
        $reserved = array(
            ":" => '!%3A!ui',
            "/" => '!%2F!ui',
            "?" => '!%3F!ui',
            "#" => '!%23!ui',
            "[" => '!%5B!ui',
            "]" => '!%5D!ui',
            "@" => '!%40!ui',
            "!" => '!%21!ui',
            "$" => '!%24!ui',
            "&" => '!%26!ui',
            "'" => '!%27!ui',
            "(" => '!%28!ui',
            ")" => '!%29!ui',
            "*" => '!%2A!ui',
            "+" => '!%2B!ui',
            "," => '!%2C!ui',
            ";" => '!%3B!ui',
            "=" => '!%3D!ui',
            "%" => '!%25!ui'
        );
        $url = preg_replace(array_values($reserved), array_keys($reserved), rawurlencode($url));
        return $url;
    }

    protected function request()
    {
        $this->headers = get_headers($this->url);
    }

    protected function parse()
    {
        //Todo: Remember => UNAVAILABLE_AFTER makes this one a bit complicated
        foreach ($this->headers as $header) {
            /*
            $parts = explode(':', mb_strtolower($header), 2);

            $tag =  $parts[1];
            switch (count($parts)) {
                case 2:
                    $this->currentUserAgent = '*';
                    $directives = $parts[1];
                    break;
                case 3:
                    $this->currentUserAgent = $parts[1];
                    break;
                default:
                    continue;
            }
            if (mb_stripos($header[0], self::PREFIX) !== 0) {
                continue;
            }
            */
        }
    }

    protected function directives()
    {
        return [
            self::DIRECTIVE_NONE,
            self::DIRECTIVE_NO_INDEX,
            self::DIRECTIVE_NO_FOLLOW,
            self::DIRECTIVE_NO_ARCHIVE,
            self::DIRECTIVE_NO_SNIPPET,
            self::DIRECTIVE_NO_ODP,
            self::DIRECTIVE_NO_TRANSLATE,
            self::DIRECTIVE_NO_IMAGE_INDEX,
            self::DIRECTIVE_UNAVAILABLE_AFTER
        ];
    }
}