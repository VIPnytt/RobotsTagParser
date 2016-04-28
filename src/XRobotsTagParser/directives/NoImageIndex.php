<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class NoImageIndex
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class NoImageIndex implements DirectiveInterface
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
