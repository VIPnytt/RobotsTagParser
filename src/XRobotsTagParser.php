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
use vipnytt\robot\URLParser;
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
        // Parse URL
        $urlParser = new URLParser(trim($url));
        if (!$urlParser->isValid()) {
            trigger_error('Invalid URL', E_USER_WARNING);
        }
        $this->url = $urlParser->encode();
        // Get headers
        $this->getHeaders($headers);
        // Parse rules
        $this->parse();
        // Set User-Agent
        $parser = new UserAgentParser($userAgent);
        $this->userAgent = $parser->match(array_keys($this->rules), self::USERAGENT_DEFAULT);
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
        }
        $this->headers = get_headers($this->url);
        if (is_array($this->headers) && !empty($this->headers)) {
            trigger_error('Unable to fetch HTTP headers', E_USER_ERROR);
            return;
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
            $this->rules[$this->currentUserAgent][$this->currentDirective] = true;
                break;
            case self::DIRECTIVE_NONE:
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_INDEX] = true;
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_FOLLOW] = true;
                break;
            case self::DIRECTIVE_UNAVAILABLE_AFTER:
                $dateTime = new DateTime();
                $dateTime->createFromFormat(DATE_RFC850, $this->currentValue);
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_UNAVAILABLE_AFTER] = $dateTime->getTimestamp();
                break;
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