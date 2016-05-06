<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class Basic
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class Basic implements DirectiveInterface
{
    /**
     * Current directive
     * @param string
     */
    protected $directive;

    /**
     * Constructor
     *
     * @param string $directive
     * @param string $rule
     */
    public function __construct($directive, $rule)
    {
        $this->directive = mb_strtolower($directive);
    }

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective()
    {
        return $this->directive;
    }

    /**
     * Get value
     *
     * @return true
     */
    public function getValue()
    {
        return true;
    }
}
