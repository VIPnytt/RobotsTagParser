<?php
/**
 * User-Agent parser
 *
 * @author Jan-Petter Gundersen (europe.jpg@gmail.com)
 */

namespace vipnytt\robot;

class UserAgentParser
{
    private $userAgent;
    private $userAgent_groups = [];

    /**
     * Constructor
     *
     * @param string $userAgent
     */
    public function __construct($userAgent)
    {
        $this->userAgent = mb_strtolower(trim($userAgent));
        $this->explode();
    }

    /**
     * Parses all possible User-Agent groups to an array
     *
     * @return array
     */
    private function explode()
    {
        $this->userAgent_groups = [$this->userAgent];
        $this->userAgent_groups[] = $this->stripVersion();
        while (strpos(end($this->userAgent_groups), '-') !== false) {
            $current = end($this->userAgent_groups);
            $this->userAgent_groups[] = substr($current, 0, strrpos($current, '-'));
        }
        $this->userAgent_groups = array_unique($this->userAgent_groups);
    }

    /**
     * Strip version number
     *
     * @return string
     */
    public function stripVersion()
    {
        if (strpos($this->userAgent, '/') !== false) {
            return explode('/', $this->userAgent, 2)[0];
        }
        return $this->userAgent;
    }

    /**
     * Find matching User-Agent
     *
     * @param array $array
     * @param string|null $fallback
     * @return string|false
     */
    public function match($array, $fallback = null)
    {
        foreach ($array as $userAgent) {
            $userAgent = mb_strtolower(trim($userAgent));
            if (in_array($userAgent, $this->userAgent_groups)) {
                return $userAgent;
            }
        }
        return isset($fallback) ? $fallback : false;
    }

    /**
     * Export all User-Agents as an array
     *
     * @return array
     */
    public function export()
    {
        return $this->userAgent_groups;
    }
}
