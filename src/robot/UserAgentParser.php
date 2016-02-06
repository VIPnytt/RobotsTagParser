<?php
/**
 * User-Agent parser
 *
 * @author Jan-Petter Gundersen (europe.jpg@gmail.com)
 */

namespace vipnytt\robot;

final class UserAgentParser
{
    private $userAgent;
    private $groups = [];

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
        $this->groups = [$this->userAgent];
        $this->groups[] = $this->stripVersion();
        while (strpos(end($this->groups), '-') !== false) {
            $current = end($this->groups);
            $this->groups[] = substr($current, 0, strrpos($current, '-'));
        }
        $this->groups = array_unique($this->groups);
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
        foreach ($this->groups as $userAgent) {
            if (in_array($userAgent, array_map('strtolower', $array))) {
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
        return $this->groups;
    }
}
