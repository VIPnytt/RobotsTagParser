<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class All
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class All implements DirectiveInterface
{
    const DIRECTIVE = 'all';
    const MEANING = 'There are no restrictions for indexing or serving. Note: this directive is the default value and has no effect if explicitly listed.';

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
