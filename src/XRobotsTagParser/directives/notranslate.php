<?php

namespace vipnytt\XRobotsTagParser\directives;


final class notranslate implements directiveInterface
{
    const DIRECTIVE = 'notranslate';

    public function __construct($value = null)
    {
    }

    public function getDirective()
    {
        return self::DIRECTIVE;
    }

    public function getArray()
    {
        return [self::DIRECTIVE => $this->getValue()];
    }

    public function getValue()
    {
        return true;
    }
}