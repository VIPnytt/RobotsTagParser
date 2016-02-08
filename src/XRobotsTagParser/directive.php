<?php

namespace vipnytt\XRobotsTagParser;

use vipnytt\XRobotsTagParser\directives;
use vipnytt\XRobotsTagParser\directives\directiveInterface;

final class directive
{
    private $object;

    public function __construct($directive, $value)
    {
        $class = __NAMESPACE__ . "\\directives\\$directive";
        if (!class_exists($class)) {
            trigger_error('Directive class does not exist', E_USER_ERROR);
        }
        $object = new $class($value);
        if (!$object instanceof directiveInterface) {
            trigger_error('Directive class invalid', E_USER_ERROR);
        }
        $this->object = $object;
        return $this->object;
    }

    public function getArray()
    {
        return $this->object->getArray();
    }
}
