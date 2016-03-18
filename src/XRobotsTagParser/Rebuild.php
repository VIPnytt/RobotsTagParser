<?php

namespace vipnytt\XRobotsTagParser;

final class Rebuild
{
    private $directiveArray;
    private $reRun = true;

    /**
     * Constructor
     *
     * @param string $directives
     */
    public function __construct($directives)
    {
        $this->directiveArray = $directives;
        while ($this->reRun) {
            $this->reRun = false;
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
        $this->reRun = true;
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
        $this->reRun = true;
    }

    /**
     * Directive unavailable_after
     *
     * @return void
     */
    private function unavailable_after()
    {
        if (
            !isset($this->directiveArray['unavailable_after'])
            || time() < date_create_from_format(DATE_RFC850, $this->directiveArray['unavailable_after'])
        ) {
            return;
        }
        $this->directiveArray['noindex'] = true;
        $this->reRun = true;
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
