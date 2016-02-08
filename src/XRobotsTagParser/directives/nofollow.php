<?php

namespace vipnytt\XRobotsTagParser\directives;


final class nofollow implements directiveInterface
{
    const DIRECTIVE = 'nofollow';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        return [self::DIRECTIVE => true];
    }
}