<?php
namespace vipnytt\XRobotsTagParser\directives;

final class NoFollow implements directiveInterface
{
    const DIRECTIVE = 'nofollow';

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
}
