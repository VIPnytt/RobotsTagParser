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
use vipnytt\robot\UserAgentParser;

class XRobotsTagParser
{
    const HEADER_RULE_IDENTIFIER = 'x-robots-tag';
    const USERAGENT_DEFAULT = 'robots';

    const DIRECTIVE_ALL = 'all';
    const DIRECTIVE_NONE = 'none';
    const DIRECTIVE_NO_ARCHIVE = 'noarchive';
    const DIRECTIVE_NO_FOLLOW = 'nofollow';
    const DIRECTIVE_NO_IMAGE_INDEX = 'noimageindex';
    const DIRECTIVE_NO_INDEX = 'noindex';
    const DIRECTIVE_NO_ODP = 'noodp';
    const DIRECTIVE_NO_SNIPPET = 'nosnippet';
    const DIRECTIVE_NO_TRANSLATE = 'notranslate';
    const DIRECTIVE_UNAVAILABLE_AFTER = 'unavailable_after';

    private $url = '';
    private $userAgent = self::USERAGENT_DEFAULT;

    private $headers = [];
    private $currentRule = '';
    private $currentUserAgent = self::USERAGENT_DEFAULT;
    private $currentDirective = '';
    private $currentValue = '';

    private $rules = [];

    /**
     * Constructor
     *
     * @param string $url
     * @param string $userAgent
     * @param array $headers
     */
    public function __construct($url, $userAgent = self::USERAGENT_DEFAULT, $headers = [])
    {
        $this->url = $this->encodeURL(trim($url));
        $this->getHeaders($headers);
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
    private static function encodeURL($url)
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

    /**
     * Request HTTP headers
     *
     * @param array $customHeaders - use these headers
     * @return void
     */
    private function getHeaders($customHeaders = [])
    {
        $this->headers = $customHeaders;
        if (is_array($this->headers) && !empty($this->headers)) {
            return;
        } else {
            $this->headers = get_headers($this->url);
            if (is_array($this->headers) && !empty($this->headers)) {
                trigger_error('Unable to fetch HTTP headers', E_USER_ERROR);
                return;
            }
        }
    }

    /**
     * Parse HTTP headers
     *
     * @return void
     */
    private function parse()
    {
        foreach ($this->headers as $header) {
            $parts = explode(':', mb_strtolower($header), 2);
            if (count($parts) < 2 || $parts[0] != self::HEADER_RULE_IDENTIFIER) {
                // Header is not a rule
                continue;
            }
            $this->currentRule = trim($parts[1]);
            $this->detectDirectives();
        }

    }

    /**
     * Detect directives in rule
     *
     * @return void
     */
    private function detectDirectives()
    {
        $rules = explode(',', $this->currentRule);
        foreach ($rules as $rule) {
            $part = explode(':', $rule, 3);
            $part[0] = trim($part[0]);
            $part[1] = isset($part[1]) ? trim($part[1]) : '';
            $part[2] = isset($part[2]) ? trim($part[2]) : '';
            if ($rules[0] === $rule && count($part) >= 2 && !in_array($part[0], $this->directiveArray())) {
                $this->currentUserAgent = $part[0];
                if (in_array($part[1], $this->directiveArray())) {
                    $this->currentDirective = $part[1];
                    $this->currentValue = $part[2];
                    $this->addRule();
                }
            } elseif (in_array($part[0], $this->directiveArray())) {
                $this->currentDirective = $part[0];
                $this->currentValue = $part[1];
                $this->addRule();
            }
        }
        $this->cleanup();
    }

    /**
     * Directives supported
     *
     * @return array
     */
    protected function directiveArray()
    {
        return [
            self::DIRECTIVE_ALL,
            self::DIRECTIVE_NONE,
            self::DIRECTIVE_NO_ARCHIVE,
            self::DIRECTIVE_NO_FOLLOW,
            self::DIRECTIVE_NO_IMAGE_INDEX,
            self::DIRECTIVE_NO_INDEX,
            self::DIRECTIVE_NO_ODP,
            self::DIRECTIVE_NO_SNIPPET,
            self::DIRECTIVE_NO_TRANSLATE,
            self::DIRECTIVE_UNAVAILABLE_AFTER
        ];
    }

    /**
     * Add rule
     *
     * @return void
     */
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
            $this->addDirectiveGeneric();
                break;
            case self::DIRECTIVE_NONE:
                $this->addDirectiveNone();
                break;
            case self::DIRECTIVE_UNAVAILABLE_AFTER:
                $this->addDirectiveUnavailableAfter();
                break;
        }
    }

    /**
     * Add generic directives
     *
     * @return void
     */
    private function addDirectiveGeneric()
    {
        $this->rules[$this->currentUserAgent][$this->currentDirective] = true;
    }

    /**
     * Add `NONE` directive
     *
     * @return void
     */
    private function addDirectiveNone()
    {
        $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_INDEX] = true;
        $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_FOLLOW] = true;
    }

    /**
     * Add `UNAVAILABLE_AFTER` directive
     *
     * @return void
     */
    private function addDirectiveUnavailableAfter()
    {
        try {
            $dateTime = DateTime::createFromFormat(DATE_RFC850, $this->currentValue);
            $this->rules[$this->currentUserAgent][self::DIRECTIVE_UNAVAILABLE_AFTER] = $dateTime->getTimestamp();
        } catch (Exception $e) {
            // Invalid date format, do nothing
        }
    }

    /**
     * CleanUp before next rule read
     *
     * @return void
     */
    private function cleanup()
    {
        $this->currentRule = '';
        $this->currentUserAgent = self::USERAGENT_DEFAULT;
        $this->currentDirective = '';
        $this->currentValue = '';
    }

    /**
     * Set UserAgent
     *
     * @param string $userAgent
     * @return void
     */
    private function setUserAgent($userAgent)
    {
        $parser = new UserAgentParser($userAgent);
        $this->userAgent = $parser->match(array_keys($this->rules), self::USERAGENT_DEFAULT);
    }

    /**
     * Return all applicable rules
     *
     * @return array
     */
    public function getRules()
    {
        $rules = [];
        // Default UserAgent
        if (isset($this->rules[self::USERAGENT_DEFAULT])) {
            $rules = array_merge($rules, $this->rules[self::USERAGENT_DEFAULT]);
        }
        // Matching UserAgent
        if (isset($this->rules[$this->userAgent])) {
            $rules = array_merge($rules, $this->rules[$this->userAgent]);
        }
        // Result
        return $rules;
    }

    /**
     * Export all rules for all UserAgents
     *
     * @return array
     */
    public function export()
    {
        return $this->rules;
    }
}