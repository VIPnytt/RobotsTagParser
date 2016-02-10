<?php

namespace vipnytt\XRobotsTagParser\directives;


final class nosnippet implements directiveInterface
{
    const DIRECTIVE = 'nosnippet';

    /**
     * Constructor
     *
     * @param string $rule
     * @param array $options
     */
    public function __construct($rule, $options = [])
    {
        foreach ($options as $key => $rule) {
            if (isset($this->$key)) {
                $this->$key = $rule;
            }
        }
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
     * Get rule array
     *
     * @return array
     */
    public function getArray()
    {
        return [self::DIRECTIVE => $this->getValue()];
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