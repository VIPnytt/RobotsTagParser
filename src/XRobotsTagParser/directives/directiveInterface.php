<?php

namespace vipnytt\XRobotsTagParser\directives;


interface directiveInterface
{
    public function __construct($value = null);

    public function getArray();
}