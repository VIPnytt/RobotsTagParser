<?php

namespace vipnytt\XRobotsTagParser\directives;


interface directiveInterface
{
    /**
     * Constructor
     *
     * @param string $rule
     * @param array $options
     */
    public function __construct($rule, $options = []);

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
     * Get rule array
     *
     * @return array
     */
    public function getArray();
}