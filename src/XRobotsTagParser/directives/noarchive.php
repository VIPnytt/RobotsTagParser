<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noarchive implements directiveInterface
{
    public function __construct($value = null)
    {
    }

    public function getValue()
    {
        return true;
    }
}