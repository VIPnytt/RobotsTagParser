<?php
/**
 * X-Robots-Tag HTTP header parser class
 *
 * @author VIP nytt (vipnytt@gmail.com)
 * @author Jan-Petter Gundersen (europe.jpg@gmail.com)
 *
 * Project:
 * @link https://github.com/VIPnytt/X-Robots-Tag-parser
 * @license https://opensource.org/licenses/MIT MIT license
 *
 * Specification:
 * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header
 */

namespace vipnytt;

use vipnytt\XRobotsTagParser\URLParser;
use vipnytt\XRobotsTagParser\UserAgentParser;

class XRobotsTagParser
{
    const HEADER_RULE_IDENTIFIER = 'x-robots-tag';
    const USERAGENT_DEFAULT = '';

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

    const DATE_FORMAT_DEFAULT = 'd M Y H:i:s T';

    private $supportedDateFormats = [
        self::DATE_FORMAT_DEFAULT,
        DATE_RFC850, // from Google specification
        'd M Y H:i:s T' // from Google examples
    ];

    private $strict = false;

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
     * @param bool $strict
     * @param array|null $headers
     */
    public function __construct($url, $userAgent = self::USERAGENT_DEFAULT, $strict = false, $headers = null)
    {
        $this->strict = $strict;

        // Parse URL
        $urlParser = new URLParser(trim($url));
        if (!$urlParser->isValid()) {
            trigger_error('Invalid URL', E_USER_WARNING);
        }
        $this->url = $urlParser->encode();
        // Get headers
        $this->useHeaders($headers);
        // Parse rules
        $this->parse();
        // Set User-Agent
        $parser = new UserAgentParser($userAgent);
        $this->userAgent = $parser->match(array_keys($this->rules), self::USERAGENT_DEFAULT);
    }

    /**
     * Request HTTP headers
     *
     * @param array|null|false $customHeaders - use these headers
     * @return bool
     */
    private function useHeaders($customHeaders = null)
    {
        if ($customHeaders === false) {
            trigger_error('Unable to fetch HTTP headers', E_USER_ERROR);
            return false;
        } elseif (!is_array($customHeaders) || empty($customHeaders)) {
            return $this->useHeaders(get_headers($this->url));
        }
        $this->headers = $customHeaders;
        return true;
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
            $pair = array_map('trim', explode(':', $rule, 2));
            if ($rules[0] === $rule && count($pair) == 2 && !in_array($pair[0], $this->directiveArray())) {
                $this->currentUserAgent = $pair[0];
                $pair = array_map('trim', explode(':', $pair[1], 2));
            }
            if (in_array($pair[0], $this->directiveArray())) {
                $this->currentDirective = $pair[0];
                $this->currentValue = isset($pair[1]) ? $pair[1] : '';
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
            case self::DIRECTIVE_ALL:
                if (!$this->strict) break;
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_ALL] = true;
                break;
            case self::DIRECTIVE_NONE:
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_NONE] = true;
                if ($this->strict) break;
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_INDEX] = true;
                $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_FOLLOW] = true;
                break;
            case self::DIRECTIVE_NO_ARCHIVE:
            case self::DIRECTIVE_NO_FOLLOW:
            case self::DIRECTIVE_NO_IMAGE_INDEX:
            case self::DIRECTIVE_NO_INDEX:
            case self::DIRECTIVE_NO_ODP:
            case self::DIRECTIVE_NO_SNIPPET:
            case self::DIRECTIVE_NO_TRANSLATE:
            $this->rules[$this->currentUserAgent][$this->currentDirective] = true;
                break;
            case self::DIRECTIVE_UNAVAILABLE_AFTER:
                if ($this->strict) $this->supportedDateFormats = [self::DATE_FORMAT_DEFAULT];
                foreach (array_unique($this->supportedDateFormats) as $format) {
                    $dateTime = date_create_from_format($format, $this->currentValue);
                    if ($dateTime === false) continue;
                    $this->rules[$this->currentUserAgent][self::DIRECTIVE_UNAVAILABLE_AFTER] = $dateTime->format(DATE_RFC850);
                    if ($this->strict) break;
                    if (time() >= $dateTime->getTimestamp()) {
                        $this->rules[$this->currentUserAgent][self::DIRECTIVE_NO_INDEX] = true;
                    }
                    break;
                }
                break;
        }
    }

    /**
     * Cleanup before next rule is read
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