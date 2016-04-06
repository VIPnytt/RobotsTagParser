<?php
namespace vipnytt\XRobotsTagParser\directives;

final class NoTranslate implements directiveInterface
{
    const DIRECTIVE = 'notranslate';

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
     * Get value
     *
     * @return bool|string|null
     */
    public function getValue()
    {
        return true;
    }
}