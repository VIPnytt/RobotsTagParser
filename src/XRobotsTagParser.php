<?php
/**
 * X-Robots-Tag HTTP header parser class
 *
 * @author VIP nytt (vipnytt@gmail.com)
 * @author Jan-Petter Gundersen (europe.jpg@gmail.com)
 *
 * Specification:
 * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header
 */

namespace vipnytt;

use DateTime;
use Exception;

class XRobotsTagParser
{
    const HTTP_HEADER_TAG = 'x-robots-tag';

    const DIRECTIVE_ALL = 'all';
    const DIRECTIVE_NO_ARCHIVE = 'noarchive';
    const DIRECTIVE_NO_FOLLOW = 'nofollow';
    const DIRECTIVE_NO_IMAGE_INDEX = 'noimageindex';
    const DIRECTIVE_NO_INDEX = 'noindex';
    const DIRECTIVE_NONE = 'none';
    const DIRECTIVE_NO_ODP = 'noodp';
    const DIRECTIVE_NO_SNIPPET = 'nosnippet';
    const DIRECTIVE_NO_TRANSLATE = 'notranslate';
    const DIRECTIVE_UNAVAILABLE_AFTER = 'unavailable_after';

    const DATETIME_RFC850 = "l, d-M-y H:i:s T";

    private $url = '';
    private $headers = [];

    private $currentRule = '';
    private $currentUserAgent = '';
    private $currentDirective = '';
    private $currentValue = '';

    private $userAgent = '*';
    private $userAgent_groups = ['*'];
    private $userAgent_match = '*';

    private $rules = [];

    /**
     * Constructor
     *
     * @param  string $url
     * @param  string $userAgent
     * @param array $optionalHeaders
     */
    public function __construct($url, $userAgent = '', $optionalHeaders = [])
    {
        $this->url = $this->encode_url(trim($url));
        $this->request($optionalHeaders);
        $this->parse();
        $this->setUserAgent(trim($userAgent));
    }

    /**
     * URL encoder according to RFC 3986
     * Returns a string containing the encoded URL with disallowed characters converted to their percentage encodings.
     * @link http://publicmind.in/blog/url-encoding/
     *
     * @param string $url
     * @return string string
     */
    private function encode_url($url)
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

    private function request($headers = [])
    {
        if (!empty($headers)) {
            $this->headers = $headers;
            return;
        }
        $this->headers = get_headers($this->url);
    }

    private function parse()
    {
        foreach ($this->headers as $header) {
            $parts = explode(':', mb_strtolower($header), 2);
            if (count($parts) < 2 || $parts[0] != self::HTTP_HEADER_TAG) {
                continue;
            }
            $this->currentRule = $parts[1];
            $this->detectDirectives();
        }
    }

    private function detectDirectives()
    {
        $rules = explode(',', $this->currentRule);
        $count = 0;
        foreach ($rules as $rule) {
            $pair = explode(':', $rule, 3);
            $pair[0] = trim($pair[0]);
            $pair[1] = isset($pair[1]) ? trim($pair[1]) : null;
            $pair[2] = isset($pair[2]) ? trim($pair[2]) : null;
            if ($count == 0 && count($pair) >= 2 && !in_array($pair[0], $this->directiveArray())) {
                $this->currentUserAgent = $pair[0];
                if (in_array($pair[1], $this->directiveArray())) {
                    $this->currentDirective = $pair[1];
                    $this->currentValue = $pair[2];
                    $this->addRule();
                }
            } elseif (in_array($pair[0], $this->directiveArray())) {
                $this->currentDirective = $pair[0];
                $this->currentValue = $pair[1];
                $this->addRule();
            }
            $count++;
        }
    }

    protected function directiveArray()
    {
        return [
            self::DIRECTIVE_ALL,
            self::DIRECTIVE_NO_ARCHIVE,
            self::DIRECTIVE_NO_FOLLOW,
            self::DIRECTIVE_NO_IMAGE_INDEX,
            self::DIRECTIVE_NO_INDEX,
            self::DIRECTIVE_NONE,
            self::DIRECTIVE_NO_ODP,
            self::DIRECTIVE_NO_SNIPPET,
            self::DIRECTIVE_NO_TRANSLATE,
            self::DIRECTIVE_UNAVAILABLE_AFTER
        ];
    }

    private function addRule()
    {
        switch ($this->currentDirective) {
            case self::DIRECTIVE_NO_ARCHIVE:
            case self::DIRECTIVE_NO_FOLLOW:
            case self::DIRECTIVE_NO_IMAGE_INDEX:
            case self::DIRECTIVE_NO_INDEX:
            case self::DIRECTIVE_NO_ODP:
            case self::DIRECTIVE_NO_SNIPPET:
            case self::DIRECTIVE_NO_TRANSLATE:
                $this->rules[$this->currentUserAgent][$this->currentDirective] = true;
                break;
            case self::DIRECTIVE_NONE:
                $this->directiveNone();
                break;
            case self::DIRECTIVE_UNAVAILABLE_AFTER:
                $this->directiveUnavailableAfter($this->currentValue);
                break;
        }
        $this->cleanup();
    }

    private function directiveNone()
    {
        $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_INDEX] = true;
        $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_FOLLOW] = true;
    }

    private function directiveUnavailableAfter($rfc850)
    {
        try {
            $dateTime = DateTime::createFromFormat(self::DATETIME_RFC850, $rfc850);
            $this->rules[$this->currentUserAgent][self::DIRECTIVE_UNAVAILABLE_AFTER] = $dateTime->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            // Invalid date format, do nothing
        }
    }

    private function cleanup()
    {
        $this->currentRule = null;
        $this->currentUserAgent = null;
        $this->currentDirective = null;
        $this->currentValue = null;
    }

    /**
     * Set UserAgent
     *
     * @param string $userAgent
     * @return void
     */
    private function setUserAgent($userAgent)
    {
        $this->userAgent = mb_strtolower($userAgent);
        if (empty($this->userAgent)) {
            $this->userAgent = '';
        }
        $this->explodeUserAgent();
    }

    /**
     * Parses all possible userAgent groups to an array
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
        $this->userAgent_groups[] = '';
        $this->userAgent_groups = array_unique($this->userAgent_groups);
        $this->determineUserAgentGroup();
    }

    /**
     * Removes the userAgent version
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
     * Determine the correct user agent group
     *
     * @return void
     */
    private function determineUserAgentGroup()
    {
        foreach ($this->userAgent_groups as $group) {
            if (isset($this->rules[$group])) {
                $this->userAgent_match = $group;
                return;
            }
        }
        $this->userAgent_match = '';
    }

    public function getRules()
    {
        if (isset($this->rules[$this->userAgent_match])) {
            return $this->rules[$this->userAgent_match];
        }
        return [self::DIRECTIVE_ALL => true];
    }

    public function export()
    {
        return $this->rules;
    }
}