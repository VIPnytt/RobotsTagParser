<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class NoFollow
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class NoFollow implements DirectiveInterface
{
    const DIRECTIVE = 'nofollow';
    const MEANING = 'Do not follow the links on this page.';

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
