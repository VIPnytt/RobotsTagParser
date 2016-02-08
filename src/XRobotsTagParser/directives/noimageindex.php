<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noimageindex implements directiveInterface
{
    const DIRECTIVE = 'noimageindex';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}