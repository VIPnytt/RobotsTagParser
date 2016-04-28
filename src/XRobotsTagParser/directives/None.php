<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class None
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class None implements DirectiveInterface
{
    const DIRECTIVE = 'none';
    const MEANING = 'Equivalent to `noindex` and `nofollow`.';

    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct($value)
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
