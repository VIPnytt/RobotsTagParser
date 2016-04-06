<?php
namespace vipnytt\XRobotsTagParser\directives;

final class None implements directiveInterface
{
    const DIRECTIVE = 'none';

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
}
