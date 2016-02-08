<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noindex implements directiveInterface
{
    const DIRECTIVE = 'noindex';

    public function __construct($value = null)
    {

    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}