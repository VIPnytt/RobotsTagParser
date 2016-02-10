<?php

namespace vipnytt\XRobotsTagParser\directives;


final class all implements directiveInterface
{
    const DIRECTIVE = 'all';

    private $strict = false;

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
        if ($this->strict) {
            return [self::DIRECTIVE => $this->getValue()];
        }
        return [];
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