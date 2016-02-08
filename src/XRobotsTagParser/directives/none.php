<?php

namespace vipnytt\XRobotsTagParser\directives;


final class none implements directiveInterface
{
    const DIRECTIVE = 'none';

    public function __construct($value = null)
    {
    }

    public function getDirective()
    {
        return self::DIRECTIVE;
    }

    public function getArray()
    {
        $result = [self::DIRECTIVE => $this->getValue()];
        $noindex = new noindex();
        $result[$noindex->getDirective()] = $noindex->getValue();
        $nofollow = new nofollow();
        $result[$nofollow->getDirective()] = $nofollow->getValue();
        return $result;
    }

    public function getValue()
    {
        return true;
    }
}