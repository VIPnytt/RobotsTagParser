<?php
namespace vipnytt;

use vipnytt\XRobotsTagParser\Directives;
use vipnytt\XRobotsTagParser\Exceptions\XRobotsTagParserException;
use vipnytt\XRobotsTagParser\Rebuild;

/**
 * Class XRobotsTagParser
 * X-Robots-Tag HTTP header parser
 *
 * @package vipnytt
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
class XRobotsTagParser
{
    /**
     * HTTP header prefix
     */
    const HEADER_RULE_IDENTIFIER = 'X-Robots-Tag';

    /**
     * Directives
     */
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

    const DIRECTIVES = [
        self::DIRECTIVE_ALL => 'There are no restrictions for indexing or serving. Note: this directive is the default value and has no effect if explicitly listed.',
        self::DIRECTIVE_NO_ARCHIVE => 'Do not show a `Cached` link in search results.',
        self::DIRECTIVE_NO_FOLLOW => 'Do not follow the links on this page.',
        self::DIRECTIVE_NO_IMAGE_INDEX => 'Do not index images on this page.',
        self::DIRECTIVE_NO_INDEX => 'Do not show this page in search results and do not show a `Cached` link in search results.',
        self::DIRECTIVE_NONE => 'Equivalent to `noindex` and `nofollow`.',
        self::DIRECTIVE_NO_ODP => 'Do not use metadata from the `Open Directory project` (http://dmoz.org/) for titles or snippets shown for this page.',
        self::DIRECTIVE_NO_SNIPPET => 'Do not show a snippet in the search results for this page.',
        self::DIRECTIVE_NO_TRANSLATE => 'Do not offer translation of this page in search results.',
        self::DIRECTIVE_UNAVAILABLE_AFTER => 'Do not show this page in search results after the specified date/time.',
    ];

    /**
     * User-Agent string
     *
     * @var string
     */
    protected $userAgent = '';

    /**
     * User-Agent for rule selection
     *
     * @var string
     */
    protected $userAgentMatch = '';

    /**
     * Current rule
     *
     * @var string
     */
    protected $currentRule = '';

    /**
     * User-Agent for the current rule
     *
     * @var string
     */
    protected $currentUserAgent;

    /**
     * Rule array
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Constructor
     *
     * @param string $userAgent
     * @param array $headers
     */
    public function __construct($userAgent = '', $headers = null)
    {
        $this->userAgent = $userAgent;
        if (isset($headers)) {
            $this->parse($headers);
        }
    }

    /**
     * Parse HTTP headers
     *
     * @param array $headers
     * @return void
     */
    public function parse(array $headers)
    {
        foreach ($headers as $header) {
            $parts = array_map('trim', mb_split(':', mb_strtolower($header), 2));
            if (count($parts) < 2 || $parts[0] != mb_strtolower(self::HEADER_RULE_IDENTIFIER)) {
                // Header is not a rule
                continue;
            }
            $this->currentRule = $parts[1];
            $this->detectDirectives();
        }
        $this->matchUserAgent();
    }

    /**
     * Detect directives in rule
     *
     * @return void
     */
    protected function detectDirectives()
    {
        $directives = array_map('trim', mb_split(',', $this->currentRule));
        $pair = array_map('trim', mb_split(':', $directives[0], 2));
        if (count($pair) == 2 && !in_array($pair[0], array_keys(self::DIRECTIVES))) {
            $this->currentUserAgent = $pair[0];
            $directives[0] = $pair[1];
        }
        foreach ($directives as $rule) {
            $directive = trim(mb_split(':', $rule, 2)[0]);
            if (in_array($directive, array_keys(self::DIRECTIVES))) {
                $this->addRule($directive);
            }
        }
        $this->cleanup();
    }

    /**
     * Add rule
     *
     * @param string $directive
     * @return void
     * @throws XRobotsTagParserException
     */
    protected function addRule($directive)
    {
        if (!isset($this->rules[$this->currentUserAgent])) {
            $this->rules[$this->currentUserAgent] = [];
        }
        switch ($directive) {
            case self::DIRECTIVE_UNAVAILABLE_AFTER:
                $object = new Directives\UnavailableAfter($directive, $this->currentRule);
                break;
            default:
                $object = new Directives\Basic($directive, $this->currentRule);
        }
        $this->rules[$this->currentUserAgent] = array_merge($this->rules[$this->currentUserAgent], [$object->getDirective() => $object->getValue()]);
    }

    /**
     * Cleanup before next rule is read
     *
     * @return void
     */
    protected function cleanup()
    {
        $this->currentRule = '';
        $this->currentUserAgent = '';
    }

    /**
     * Find the most rule-matching User-Agent
     *
     * @return string
     */
    protected function matchUserAgent()
    {
        $userAgentParser = new UserAgentParser($this->userAgent);
        $match = $userAgentParser->match(array_keys($this->rules));
        $this->userAgentMatch = ($match !== false) ? $match : '';
        return $this->userAgentMatch;
    }

    /**
     * Return all applicable rules
     *
     * @param bool $raw
     * @return array
     */
    public function getRules($raw = false)
    {
        $rules = [];
        // Default UserAgent
        if (isset($this->rules[''])) {
            $rules = array_merge($rules, $this->rules['']);
        }
        // Matching UserAgent
        if (isset($this->rules[$this->userAgentMatch])) {
            $rules = array_merge($rules, $this->rules[$this->userAgentMatch]);
        }
        if (!$raw) {
            $rebuild = new Rebuild($rules);
            $rules = $rebuild->getResult();
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

    /**
     * Get the meaning of an Directive
     *
     * @param string $directive
     * @return string
     * @throws XRobotsTagParserException
     */
    public function getDirectiveMeaning($directive)
    {
        $directive = mb_strtolower($directive);
        if (!in_array($directive, array_keys(self::DIRECTIVES))) {
            throw new XRobotsTagParserException('Unknown directive');
        }
        return self::DIRECTIVES[$directive];
    }
}
