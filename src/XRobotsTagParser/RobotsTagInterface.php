<?php
namespace vipnytt\XRobotsTagParser;

/**
 * Interface RobotsTagInterface
 *
 * @package vipnytt\XRobotsTagParser
 */
interface RobotsTagInterface
{
    /**
     * HTTP header prefix
     */
    const HEADER_RULE_IDENTIFIER = 'X-Robots-Tag';

    /**
     * Directives
     * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#valid-indexing--serving-directives
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

    /**
     * Directive meanings
     * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#valid-indexing--serving-directives
     */
    const DIRECTIVES = [
        self::DIRECTIVE_ALL => 'There are no restrictions for indexing or serving. Note: this directive is the default value and has no effect if explicitly listed.',
        self::DIRECTIVE_NONE => 'Equivalent to `noindex` and `nofollow`.',
        self::DIRECTIVE_NO_ARCHIVE => 'Do not show a `Cached` link in search results.',
        self::DIRECTIVE_NO_FOLLOW => 'Do not follow the links on this page.',
        self::DIRECTIVE_NO_IMAGE_INDEX => 'Do not index images on this page.',
        self::DIRECTIVE_NO_INDEX => 'Do not show this page in search results and do not show a `Cached` link in search results.',
        self::DIRECTIVE_NO_ODP => 'Do not use metadata from the `Open Directory project` (http://dmoz.org/) for titles or snippets shown for this page.',
        self::DIRECTIVE_NO_SNIPPET => 'Do not show a snippet in the search results for this page.',
        self::DIRECTIVE_NO_TRANSLATE => 'Do not offer translation of this page in search results.',
        self::DIRECTIVE_UNAVAILABLE_AFTER => 'Do not show this page in search results after the specified date/time.',
    ];
}
