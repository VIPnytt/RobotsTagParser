<?php

namespace vipnytt\XRobotsTagParser\directives;


final class none implements directiveInterface
{
    const DIRECTIVE = 'none';

    public function __construct($value = null)
    {
    }

    public function getArray()
    {
        $result = [self::DIRECTIVE => true];
        /*$test = new noindex();
        $result = array_merge($result, $test->getArray());
        $test = new nofollow();
        $result = array_merge($result, $test->getArray());*/
        return $result;
    }
}