<?php

namespace vipnytt\XRobotsTagParser\directives;


final class nosnippet implements directiveInterface
{
    const DIRECTIVE = 'nosnippet';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}