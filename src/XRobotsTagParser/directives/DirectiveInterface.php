<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Interface DirectiveInterface
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
interface DirectiveInterface
{
    /**
     * Constructor
     *
     * @param string $rule
     */
    public function __construct($rule);

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective();

    /**
     * Get value
     *
     * @return bool|string|null
     */
    public function getValue();

    /**
     * Get meaning
     *
     * @return string
     */
    public function getMeaning();
}
