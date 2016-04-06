<?php
namespace vipnytt\XRobotsTagParser\directives;

interface directiveInterface
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
}
