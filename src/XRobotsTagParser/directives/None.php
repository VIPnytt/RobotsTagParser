<?php
namespace vipnytt\XRobotsTagParser\directives;

final class None implements directiveInterface
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
