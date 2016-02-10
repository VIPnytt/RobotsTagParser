<?php

namespace vipnytt\XRobotsTagParser\directives;


final class none implements directiveInterface
{
    const DIRECTIVE = 'none';

    private $value;

    private $strict = false;

    /**
     * Constructor
     *
     * @param string $value
     * @param array $options
     */
    public function __construct($value, $options = [])
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
        $result = [self::DIRECTIVE => $this->getValue()];
        if (!$this->strict) {
            $noindex = new noindex(str_ireplace(self::DIRECTIVE, 'noindex', $this->value));
            $result[$noindex->getDirective()] = $noindex->getValue();
            $nofollow = new nofollow(str_ireplace(self::DIRECTIVE, 'nofollow', $this->value));
            $result[$nofollow->getDirective()] = $nofollow->getValue();
        }
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