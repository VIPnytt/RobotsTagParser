<?php

namespace vipnytt\XRobotsTagParser\directives;


final class noimageindex implements directiveInterface
{
    public function __construct($value = null)
    {
    }

    public function getValue()
    {
        return true;
    }
}