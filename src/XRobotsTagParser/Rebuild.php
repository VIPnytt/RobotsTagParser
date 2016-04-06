<?php
namespace vipnytt\XRobotsTagParser;

final class Rebuild
{
    private $directiveArray;

    /**
     * Constructor
     *
     * @param string $directives
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
            $this->noindex();
            $this->none();
            $this->unavailable_after();
        }
    }

    /**
     * Directive all
     *
     * @return void
     */
    private function all()
    {
        if (!isset($this->directiveArray['all'])) {
            return;
        }
        unset($this->directiveArray['all']);
    }

    /**
     * Directive noindex
     *
     * @return void
     */
    private function noindex()
    {
        if (!isset($this->directiveArray['noindex'])) {
            return;
        }
        $this->directiveArray['noarchive'] = true;
    }

    /**
     * Directive none
     *
     * @return void
     */
    private function none()
    {
        if (!isset($this->directiveArray['none'])) {
            return;
        }
        $this->directiveArray['noindex'] = true;
        $this->directiveArray['nofollow'] = true;
    }

    /**
     * Directive unavailable_after
     *
     * @return void
     */
    private function unavailable_after()
    {
        if (!isset($this->directiveArray['unavailable_after'])) {
            return;
        }
        $dateTime = date_create_from_format(DATE_RFC850, $this->directiveArray['unavailable_after']);
        if ($dateTime !== false && time() >= $dateTime->getTimestamp()) {
            $this->directiveArray['noindex'] = true;
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
