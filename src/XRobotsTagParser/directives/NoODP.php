<?php
namespace vipnytt\XRobotsTagParser\directives;

/**
 * Class NoODP
 *
 * @package vipnytt\XRobotsTagParser\directives
 */
final class NoODP implements DirectiveInterface
{
    const DIRECTIVE = 'noodp';
    const MEANING = 'Do not use metadata from the `Open Directory project` (http://dmoz.org/) for titles or snippets shown for this page.';

    /**
     * Constructor
     *
     * @param string $rule
     */
    public function __construct($rule)
    {

    }

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective()
    {
        return self::DIRECTIVE;
    }

    /**
     * Get value
     *
     * @return bool|string|null
     */
    public function getValue()
    {
        return true;
    }

    /**
     * Get directive meaning
     *
     * @return string
     */
    public function getMeaning()
    {
        return self::MEANING;
    }
}
