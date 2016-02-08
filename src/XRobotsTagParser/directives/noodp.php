<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noodp implements directiveInterface
{
    const DIRECTIVE = 'noodp';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}