<?php
namespace vipnytt\XRobotsTagParser;

/**
 * Class Rebuild
 *
 * @package vipnytt\XRobotsTagParser
 */
final class Rebuild implements RobotsTagInterface
{
    private $directiveArray;

    /**
     * Constructor
     *
     * @param array $directives
     */
    public function __construct($directives)
    {
        $this->directiveArray = $directives;
        $this->parse();
    }

    /**
     * parse
     *
     * @return void
     */
    private function parse()
    {
        $past = [];
        while ($past !== $this->directiveArray) {
            $past = $this->directiveArray;
            $this->all();
            $this->noIndex();
            $this->none();
            $this->unavailableAfter();
        }
    }

    /**
     * Directive all
     *
     * @return void
     */
    private function all()
    {
        if (!isset($this->directiveArray[self::DIRECTIVE_ALL])) {
            return;
        }
        unset($this->directiveArray[self::DIRECTIVE_ALL]);
    }

    /**
     * Directive noindex
     *
     * @return void
     */
    private function noIndex()
    {
        if (!isset($this->directiveArray[self::DIRECTIVE_NO_INDEX])) {
            return;
        }
        $this->directiveArray[self::DIRECTIVE_NO_ARCHIVE] = true;
    }

    /**
     * Directive none
     *
     * @return void
     */
    private function none()
    {
        if (!isset($this->directiveArray[self::DIRECTIVE_NONE])) {
            return;
        }
        $this->directiveArray[self::DIRECTIVE_NO_INDEX] = true;
        $this->directiveArray[self::DIRECTIVE_NO_FOLLOW] = true;
    }

    /**
     * Directive unavailable_after
     *
     * @return void
     */
    private function unavailableAfter()
    {
        if (!isset($this->directiveArray[self::DIRECTIVE_UNAVAILABLE_AFTER])) {
            return;
        }
        $dateTime = date_create_from_format(DATE_RFC850, $this->directiveArray[self::DIRECTIVE_UNAVAILABLE_AFTER]);
        if ($dateTime !== false && time() >= date_timestamp_get($dateTime)) {
            $this->directiveArray[self::DIRECTIVE_NO_INDEX] = true;
        }
    }

    /**
     * Result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->directiveArray;
    }
}
