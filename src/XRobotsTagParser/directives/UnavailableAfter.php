<?php
namespace vipnytt\XRobotsTagParser\directives;

/**
 * Class UnavailableAfter
 *
 * @package vipnytt\XRobotsTagParser\directives
 */
final class UnavailableAfter implements DirectiveInterface
{
    const DIRECTIVE = 'unavailable_after';
    const MEANING = 'Do not show this page in search results after the specified date/time.';

    const DATE_GOOGLE = 'd M Y H:i:s T';

    private $supportedDateFormats = [
        DATE_RFC850,
        self::DATE_GOOGLE
    ];

    private $value;

    /**
     * Constructor
     *
     * @param string $rule
     */
    public function __construct($rule)
    {
        $this->value = $rule;
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
     * @return string|null
     */
    public function getValue()
    {
        $parts = explode(',', trim(substr($this->value, mb_stripos($this->value, self::DIRECTIVE) + mb_strlen(self::DIRECTIVE) + 1)));
        $count = count($parts);
        for ($i = 1; $i <= $count; $i++) {
            foreach ($this->supportedDateFormats as $format) {
                $dateTime = date_create_from_format($format, trim(implode(',', array_slice($parts, 0, $i))));
                if ($dateTime !== false) {
                    return $dateTime->format(DATE_RFC850);
                }
            }
        }
        return null;
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

