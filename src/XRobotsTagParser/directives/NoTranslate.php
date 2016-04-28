<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class NoTranslate
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class NoTranslate implements DirectiveInterface
{
    const DIRECTIVE = 'notranslate';
    const MEANING = 'Do not offer translation of this page in search results.';

    /**
     * Constructor
     *
     * @param string $rule
     */
    public function __construct($rule)
    {

    }

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective()
    {
        return self::DIRECTIVE;
    }

    /**
     * Get value
     *
     * @return bool|string|null
     */
    public function getValue()
    {
        return true;
    }

    /**
     * Get directive meaning
     *
     * @return string
     */
    public function getMeaning()
    {
        return self::MEANING;
    }
}
