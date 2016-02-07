<?php

namespace vipnytt\XRobotsTagParser\directives;


final class nofollow implements directiveInterface
{
    public function __construct($value = null)
    {
    }

    public function getValue()
    {
        return true;
    }
}