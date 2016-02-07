<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noindex implements directiveInterface
{
    public function __construct($value = null)
    {
    }

    public function getValue()
    {
        return true;
    }
}