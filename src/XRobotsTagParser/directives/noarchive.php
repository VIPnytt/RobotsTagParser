<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noarchive implements directiveInterface
{
    const DIRECTIVE = 'noarchive';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}