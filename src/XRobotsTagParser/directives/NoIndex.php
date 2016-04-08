<?php
namespace vipnytt\XRobotsTagParser\directives;

final class NoIndex implements DirectiveInterface
{
    const DIRECTIVE = 'noindex';
    const MEANING = 'Do not show this page in search results and do not show a `Cached` link in search results.';

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
