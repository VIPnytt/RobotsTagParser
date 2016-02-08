<?php

namespace vipnytt\XRobotsTagParser\directives;


final class notranslate implements directiveInterface
{
    const DIRECTIVE = 'notranslate';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}