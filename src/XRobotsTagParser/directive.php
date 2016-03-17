<?php

namespace vipnytt\XRobotsTagParser;

use vipnytt\XRobotsTagParser\directives;
use vipnytt\XRobotsTagParser\directives\directiveInterface;

final class directive
{
    private $object;

    /**
     * Constructor
     *
     * @param string $directive
     * @param string $rule
     * @param array $options
     */
    public function __construct($directive, $rule, $options)
    {
        $class = __NAMESPACE__ . "\\directives\\$directive";
        if (!class_exists($class)) {
            trigger_error('Directive class does not exist', E_USER_ERROR);
        }
        $object = new $class($rule, $options);
        if (!$object instanceof directiveInterface) {
            trigger_error('Directive class invalid', E_USER_ERROR);
        }
        $this->object = $object;
    }

    /**
     * Get rule array
     *
     * @return array
     */
    public function getArray()
    {
        return $this->object->getArray();
    }
}
