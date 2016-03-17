<?php

namespace vipnytt\XRobotsTagParser\directives;


final class none implements directiveInterface
{
    const DIRECTIVE = 'none';

    /**
     * Constructor
     *
     * @param string $value
     * @param array $options
     */
    public function __construct($value, $options = [])
    {
        foreach ($options as $key => $value) {
            if (isset($this->$key)) {
                $this->$key = $value;
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
        $result = [self::DIRECTIVE => $this->getValue()];
        return $result;
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