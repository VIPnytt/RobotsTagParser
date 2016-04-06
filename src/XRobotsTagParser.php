<?php
namespace vipnytt;

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

use vipnytt\XRobotsTagParser\Rebuild;
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

    private $url = '';
    private $userAgent = self::USERAGENT_DEFAULT;

    private $headers = [];
    private $currentRule = '';
    private $currentUserAgent = self::USERAGENT_DEFAULT;

    private $options = [];
    private $rules = [];

    /**
     * Constructor
     *
     * @param string $url
     * @param string $userAgent
     * @param array $options
     */
    public function __construct($url, $userAgent = self::USERAGENT_DEFAULT, $options = [])
    {
        // Parse URL
        $urlParser = new URLParser(trim($url));
        if (!$urlParser->isValid()) {
            trigger_error('Invalid URL', E_USER_WARNING);
        }
        // Encode URL
        $this->url = $urlParser->encode();
        // Set any optional options
        $this->options = $options;
        // Get headers
        $this->getHeaders();
        // Parse rules
        $this->parse();
        // Set User-Agent
        $parser = new UserAgentParser($userAgent);
        $this->userAgent = $parser->match(array_keys($this->rules), self::USERAGENT_DEFAULT);
    }

    /**
     * Request HTTP headers
     *
     * @return bool
     */
    private function getHeaders()
    {
        if (isset($this->options['headers'])) {
            $this->headers = $this->options['headers'];
            return true;
        }
        $this->headers = get_headers($this->url);
        if ($this->headers === false) {
            trigger_error('Unable to fetch HTTP headers', E_USER_ERROR);
            return false;
        }
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
            $parts = array_map('trim', explode(':', mb_strtolower($header), 2));
            if (count($parts) < 2 || $parts[0] != self::HEADER_RULE_IDENTIFIER) {
                // Header is not a rule
                continue;
            }
            $this->currentRule = $parts[1];
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
        $directives = array_map('trim', explode(',', $this->currentRule));
        $pair = array_map('trim', explode(':', $directives[0], 2));
        if (count($pair) == 2 && !in_array($pair[0], array_keys($this->directiveClasses()))) {
            $this->currentUserAgent = $pair[0];
            $directives[0] = $pair[1];
        }
        foreach ($directives as $rule) {
            $directive = trim(explode(':', $rule, 2)[0]);
            if (in_array($directive, array_keys($this->directiveClasses()))) {
                $this->addRule($this->directiveClasses()[$directive]);
            }
        }
        $this->cleanup();
    }

    /**
     * Array of directives and their class names
     *
     * @return array
     */
    protected function directiveClasses()
    {
        return [
            self::DIRECTIVE_ALL => 'All',
            self::DIRECTIVE_NO_ARCHIVE => 'NoArchive',
            self::DIRECTIVE_NO_FOLLOW => 'NoFollow',
            self::DIRECTIVE_NO_IMAGE_INDEX => 'NoImageIndex',
            self::DIRECTIVE_NO_INDEX => 'NoIndex',
            self::DIRECTIVE_NONE => 'None',
            self::DIRECTIVE_NO_ODP => 'NoODP',
            self::DIRECTIVE_NO_SNIPPET => 'NoSnippet',
            self::DIRECTIVE_NO_TRANSLATE => 'NoTranslate',
            self::DIRECTIVE_UNAVAILABLE_AFTER => 'UnavailableAfter',
        ];
    }

    /**
     * Add rule
     *
     * @param string $directive
     * @return void
     */
    private function addRule($directive)
    {
        if (!isset($this->rules[$this->currentUserAgent])) {
            $this->rules[$this->currentUserAgent] = [];
        }
        $class = __NAMESPACE__ . "\\XRobotsTagParser\\directives\\$directive";
        $object = new $class($this->currentRule, $this->options);
        if (!$object instanceof XRobotsTagParser\directives\directiveInterface) {
            trigger_error('Directive class invalid', E_USER_ERROR);
        }
        $this->rules[$this->currentUserAgent] = array_merge($this->rules[$this->currentUserAgent], [$object->getDirective() => $object->getValue()]);
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
        if (isset($this->rules[self::USERAGENT_DEFAULT])) {
            $rules = array_merge($rules, $this->rules[self::USERAGENT_DEFAULT]);
        }
        // Matching UserAgent
        if (isset($this->rules[$this->userAgent])) {
            $rules = array_merge($rules, $this->rules[$this->userAgent]);
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
}