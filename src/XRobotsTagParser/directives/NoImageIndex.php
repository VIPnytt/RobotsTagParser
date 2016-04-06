<?php
namespace vipnytt\XRobotsTagParser\directives;

final class NoImageIndex implements directiveInterface
{
    const DIRECTIVE = 'noimageindex';
    const MEANING = 'Do not index images on this page.';

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
